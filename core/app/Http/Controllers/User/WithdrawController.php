<?php

namespace App\Http\Controllers\User;

use App\Models\WithdrawMethod;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function withdraw()
    {
        $pageTitle = 'Withdraw Methods';
        $methods = WithdrawMethod::where('status', 1)->get();

        return view("{$this->activeTemplate}user.withdraw.withdraw", compact('methods', 'pageTitle'));
    }

    public function withdrawInfo(Request $request)
    {

        $pageTitle = 'Withdraw Information';
        $methodId = $request->get('method', $request->session()->get('withdraw_method_id'));
        $request->session()->put('withdraw_method_id', $methodId);

        $method = WithdrawMethod::find($methodId);
        $balance = auth()->user()->balance;
        $general = GeneralSetting::first();

        return view("{$this->activeTemplate}user.withdraw.information", compact('method', 'balance', 'general', 'pageTitle'));
    }

    public function withdrawVerification(Request $request)
    {
        
        $pageTitle = 'Withdraw Verification';
        $methodId = $request->session()->get('withdraw_method_id');
        if (!$methodId) {
            return back()->with('error', 'Withdrawal method not found. Please start over.');
        }

        $method = WithdrawMethod::findOrFail($methodId);

        if ($request->amount) {
            $this->validateWithdrawalRequest($request, $method);
            $user = auth()->user();
            $user->ver_code = verificationCode(6);
            $user->ver_code_send_at = Carbon::now();
            $user->save();
            notify($user, "EVER_CODE", ['code' => $user->ver_code], ["email"]);
            $request->session()->put('withdraw_request_data', $request->all());
        }

        return view("{$this->activeTemplate}user.withdraw.verification", compact('method', 'pageTitle'));
    }

    private function validateWithdrawalRequest(Request $request, $method)
    {
        $rules = ['amount' => 'required|numeric|min:1'];
        if (is_array(json_decode($method->input_form, true))) {
            foreach (json_decode($method->input_form, true) as $data) {
                $rules[$data['field_name']] = $data['validation'];
            }
        }

        $request->validate($rules);
        $method = WithdrawMethod::find($method->id);
        $general = GeneralSetting::first();

        if ($request->amount > auth()->user()->balance) {
            throw ValidationException::withMessages(['amount' => 'Your balance is not enough']);
        }

        if ($request->amount < $method->min_amount) {
            throw ValidationException::withMessages(['amount' => 'You can\'t withdraw under ' . (int)$method->min_amount . $method->symbol]);
        }

        if ($request->amount > $method->max_amount) {
            throw ValidationException::withMessages(['amount' => 'You can\'t withdraw more than ' . (int)$method->max_amount . $method->symbol]);
        }
    }

    public function sendVerifyCode()
    {
        $user = auth()->user();
        if ($this->checkCodeValidity($user)) {
            $delay = $user->ver_code_send_at->addMinutes(2)->diffInSeconds(Carbon::now());
            throw ValidationException::withMessages(['resend' => "Please try after {$delay} seconds"]);
        }


        $user->ver_code = verificationCode(6);
        $user->ver_code_send_at = Carbon::now();
        $user->save();

        notify($user, "EVER_CODE", ['code' => $user->ver_code], ["email"]);
        $notify[] = ['success', 'Verification code sent successfully'];
        return back()->withNotify($notify);
    }

    public function withdrawConfirm(Request $request)
    {
        $request->validate(['code' => 'required']);
        $user = auth()->user();

        if ($user->ver_code == $request->code) {
            $this->processWithdrawal($request);
        }

        $notify[] = ['success', 'Withdrawal request has been sent successfully.'];
        return redirect()->route('user.withdraw.history')->withNotify($notify);
    }

    private function processWithdrawal(Request $request)
    {
        $withdrawRequest = $request->session()->get('withdraw_request_data');
        $methodId = $request->session()->get('withdraw_method_id');
        $user = auth()->user();
        $user->ver_code = verificationCode(6);
        $user->save();
        if (!$withdrawRequest || !$methodId) {
            return redirect()->route('user.withdraw')->with('error', 'Invalid session data. Please start over.');
        }
        $method = WithdrawMethod::find($methodId);

        $amount = $withdrawRequest['amount'] / $method->rate;

        $withdrawTransaction = new Transaction();
        $withdrawTransaction->user_id = $user->id;
        $withdrawTransaction->amount = $amount;

        if ($method->fixed_charge) {
            $withdrawTransaction->charge =  $method->fixed_charge;
        } elseif ($method->percent_charge) {
            $withdrawTransaction->charge =  $amount * ($method->percent_charge / 100);
        }
        $withdrawTransaction->type = 6;
        $withdrawTransaction->save();


        $withdraw = new Withdrawal();
        $withdraw->transaction_id = $withdrawTransaction->id;
        $withdraw->method_id = $methodId;
        $withdraw->data = json_encode(array_slice($withdrawRequest, 2));
        $withdraw->save();

        notify($user, 'WITHDRAWAL_REQUEST', [

            'method_name' => $withdraw->method->name,

            'method_currency' => $withdraw->method->currency,

            'amount' => showAmount($withdraw->amount),


        ]);


        $request->session()->forget(['withdraw_request_data', 'withdraw_method_id']);
    }

    protected function checkCodeValidity($user, $addMin = 2)
    {
        if (!$user->ver_code_send_at) {
            return false;
        }

        return $user->ver_code_send_at->addMinutes($addMin) > Carbon::now();
    }
}
