<?php

namespace App\Http\Controllers\Admin;

use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\WithdrawMethod;
use App\Models\GeneralSetting;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class WithdrawalController extends Controller
{

    public function pending()
    {

        $pageTitle = 'Pending Withdrawals';

        $withdraws = $this->withdrawData('pending');

        return view('admin.withdraw.log', compact('pageTitle', 'withdraws'));

    }



    public function approved()

    {
        $pageTitle = 'Approved Withdrawals';

        $withdraws = $this->withdrawData('approved');

        return view('admin.withdraw.log', compact('pageTitle', 'withdraws'));
    }



    public function rejected()

    {

        $pageTitle = 'Rejected Withdrawals';

        $withdraws = $this->withdrawData('rejected');

        return view('admin.withdraw.log', compact('pageTitle', 'withdraws'));

    }







    public function withdraw()

    {

        $pageTitle = 'Withdrawal History';

        $withdrawData = $this->withdrawData($scope = null, $summery = true);

        $withdraws = $withdrawData['data'];

        $summery = $withdrawData['summery'];

        $successful = $summery['successful'];

        $pending = $summery['pending'];

        $rejected = $summery['rejected'];

        $total = $summery['total'];

        return view('admin.withdraw.log', compact('pageTitle', 'withdraws','successful','pending','rejected','total'));

    }



    protected function withdrawData($scope = null,$summery = false)

    {

        if ($scope) {

            $withdraws = Withdrawal::$scope()->with(['user', 'method']);

        }else{

            $withdraws = Withdrawal::with(['user', 'method']);

        }



        $request = request();

        //search

        if ($request->search) {

            $search = request()->search;

            $withdraws = $withdraws->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%$search%")
                  ->orWhereHas('transaction.user', function ($userQuery) use ($search) {
                      $userQuery->where('username', 'like', "%$search%");
                  });
            });

        }



        //date search

        if($request->date) {

            $date = explode('-',$request->date);

            $request->merge([

                'start_date'=> trim(@$date[0]),

                'end_date'  => trim(@$date[1])

            ]);

            $request->validate([

                'start_date'    => 'required|date_format:m/d/Y',

                'end_date'      => 'nullable|date_format:m/d/Y'

            ]);

            if($request->end_date) {

                $endDate = Carbon::parse($request->end_date)->addHours(23)->addMinutes(59)->addSecond(59);

                $withdraws   = $withdraws->whereBetween('created_at', [Carbon::parse($request->start_date), $endDate]);

            }else{

                $withdraws   = $withdraws->whereDate('created_at', Carbon::parse($request->start_date));

            }

        }



        if (!$summery) {

            return $withdraws->orderBy('id','desc')->paginate(getPaginate());

        }else{

            $successful = clone $withdraws;

            $pending = clone $withdraws;

            $rejected = clone $withdraws;

            $total = clone $withdraws;



            $successfulSummery = $successful->approved()->count();

            $pendingSummery = $pending->pending()->count();

            $rejectedSummery = $rejected->rejected()->count();

            $totalSummery = $total->count();



            return [

                'data'=>$withdraws->orderBy('id','desc')->paginate(getPaginate()),

                'summery'=>[

                    'successful'=>$successfulSummery,

                    'pending'=>$pendingSummery,

                    'rejected'=>$rejectedSummery,

                    'total'=>$totalSummery,

                ]

            ];

        }

    }



    public function details($id)

    {

        $general = GeneralSetting::first();

        $withdraw = Withdrawal::where('id', $id)->with(['user', 'method'])->firstOrFail();

        $pageTitle = $withdraw->transaction->user->username.' requested ' . showAmount($withdraw->transaction->amount) . ' '.$general->cur_text;

        $details = ($withdraw->data != null) ? json_encode($withdraw->data) : null;

        return view('admin.withdraw.detail', compact('pageTitle', 'withdraw','details'));

    }





    public function approve($id)

    {

        $withdraw = Withdrawal::where('id',$id)->firstOrFail();

        $transaction = Transaction::where('id',$withdraw->transaction_id)->firstOrFail();




        

        if ($withdraw->method_id == 1){

            $geftCard = $this->getGiftCard($withdraw->transaction->amount);

            notify($transaction->user, 'GIFT_CARD', [

                'gift_code' => $geftCard,

                'amount' => showAmount($withdraw->amount)

            ]);
        }


        $transaction->status = 1;

        $transaction->save();

        notify($transaction->user, 'WITHDRAWAL_APPROVE', [

            'method_name' => $withdraw->method->name,

            'method_currency' => $withdraw->method->currency,

            'amount' => showAmount($withdraw->amount),


        ]);


        $notify[] = ['success', 'Withdrawal request approved successfully'];
        return to_route('admin.withdraw.pending')->withNotify($notify);

    }



    public function reject(Request $request)

    {

        $request->validate([

            'id' => 'required|integer',

            'message' => 'required|string|max:255'

        ]);

        $withdraw = Withdrawal::where('id',$request->id)->firstOrFail();

        $transaction = Transaction::where('id',$withdraw->transaction_id)->firstOrFail();

        $withdraw->admin_feedback = $request->message;

        $transaction->status = 2;

        $transaction->type = $request->banned ? 7:6;

        $withdraw->save();
        $transaction->save();



        notify($transaction->user, 'WITHDRAWAL_REJECT', [

            'method_name' => $withdraw->method->name,

            'method_currency' => $withdraw->method->currency,

            'amount' => showAmount($withdraw->amount),

            'rejection_message' => $request->message

        ]);



        $notify[] = ['success', 'Withdrawal request rejected successfully'];

        return  to_route('admin.withdraw.pending')->withNotify($notify);



    }



    private function getGiftCard($amount){
        $response = Http::post('http://halagiftcards.com/api/generate-gift-card', ['amount' => $amount]);

        if ($response->successful()) {
            $giftCardDetails = $response->json();
            return $giftCardDetails['gift_card']['code'];
        }
    
        return redirect()->back()->withErrors(['message' => 'Failed to generate gift card']);
    }
}
