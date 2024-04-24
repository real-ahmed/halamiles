<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Click;
use App\Models\GeneralSetting;
use App\Models\ClaimIssue;
use App\Models\Claim;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class Clicks extends Controller
{
    public function history()
    {
        $pageTitle = "Clicks History";
        $clicks = Click::where('user_id', auth()->user()->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.click.history', compact('pageTitle', 'clicks'));
    }


    public function claim($id)
    {
        $click = Click::findOrFail($id);
        if (!$click->IsClaim) {
            $notify[] = ['error', 'can\'t claim on this click.'];
            return redirect()->route('user.clicks.history')->withNotify($notify);
        }
        $pageTitle = "Claim Request";
        $issues = ClaimIssue::all();
        $general = GeneralSetting::first();
        return view($this->activeTemplate . 'user.click.form', compact('pageTitle', 'click', 'issues', 'general'));
    }


    public function claimConfirm(Request $request)
    {
        $pageTitle = "Confirm The Claim Request";
        $request->validate([
            'issue_id' => 'required|exists:claim_issues,id',
            'model' => 'required|string',
            'order_date' => 'required|date',
            'order_amount' => 'required|numeric|min:0',
            'click_id' => 'required|exists:clicks,id'
        ]);
        $click = Click::findOrFail($request->click_id);
        if (!$click->IsClaim) {
            $notify[] = ['error', 'can\'t claim on this click.'];
            return redirect()->route('user.clicks.history')->withNotify($notify);
        }
        $issue = ClaimIssue::findOrFail($request->issue_id);
        $purchaseDate = $request->order_date;
        $orderAmount = $request->order_amount;
        $cashbackAmount = $this->getCashback($click, $orderAmount);
        $general = GeneralSetting::first();
        return view($this->activeTemplate . 'user.click.confirm', compact('pageTitle', 'click', 'issue', 'general', 'purchaseDate', 'orderAmount', 'cashbackAmount'));
    }


    public function saveClaim(Request $request)
    {

        $notify[] = ['success', 'Claim Request Successfully'];
        $request->validate([
            'issue_id' => 'required|exists:claim_issues,id',
            'order_date' => 'required|date',
            'order_amount' => 'required|numeric|min:0',
            'click_id' => 'required|exists:clicks,id',
            'order_number' => 'required|numeric|min:0',
            'order_name' => 'required|string',
            'agree' => 'required'
        ]);
        $click = Click::findOrFail($request->click_id);
        if (!$click->IsClaim) {
            $notify[] = ['error', 'can\'t claim on this click.'];
            return redirect()->route('user.clicks.history')->withNotify($notify);
        }

        $claim = new Claim();
        $claim->issue_id = $request->issue_id;
        $orderDate = Carbon::parse($request->order_date);
        $claim->order_date = $orderDate->toDateTimeString();
        $claim->order_amount = $request->order_amount;
        $claim->click_id = $request->click_id;
        $claim->order_number = $request->order_number;
        $claim->order_name = $request->order_name;
        $claim->code = $request->code;
        $claim->another_link = $request->code;
        $claim->private_browser = $request->browser;
        $claim->vpn = $request->vpn;
        $claim->save();



        return redirect()->route('user.clicks.claims')->withNotify($notify);
    }


    public function claims()
    {
        $pageTitle = "Claims History";
        $claims = Claim::whereHas('click', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->latest()->paginate(getPaginate());

        return view($this->activeTemplate . 'user.click.claims', compact('pageTitle', 'claims'));
    }



    private function getCashback($click, $orderAmount)
    {
        $cashback_type = $click->model->cashbacktype->id;
        $cashback = $click->model->cashback;

        if ($cashback_type == 1 || $cashback_type == 3) {
            $amount = (float) $cashback;
        } elseif ($cashback_type == 2) {
            $orderAmount = round($orderAmount, 2);
            $amount = ((float) $cashback / 100) * $orderAmount;
            $cashback_type = 1;
        }
        return $amount;
    }
}
