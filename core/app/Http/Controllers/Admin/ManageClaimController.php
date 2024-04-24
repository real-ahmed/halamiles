<?php

namespace App\Http\Controllers\Admin;

use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Transaction;
use App\Models\ClickTransaction;


class ManageClaimController extends Controller
{
    public function pending()
    {

        $pageTitle = 'Pending Claims';

        $claims = $this->withdrawData('pending');

        return view('admin.claim.log', compact('pageTitle', 'claims'));
    }



    public function approved()

    {
        $pageTitle = 'Approved Withdrawals';

        $claims = $this->withdrawData('approved');

        return view('admin.claim.log', compact('pageTitle', 'claims'));
    }



    public function rejected()

    {

        $pageTitle = 'Rejected Withdrawals';

        $claims = $this->withdrawData('rejected');

        return view('admin.claim.log', compact('pageTitle', 'claims'));
    }











    protected function withdrawData($scope = null)

    {

        if ($scope) {

            $claims = Claim::$scope()->with(['issue', 'click']);
        } else {

            $claims = Claim::with(['issue', 'click']);
        }



        $request = request();

        //search

        if ($request->search) {

            $search = request()->search;

            $claims = $claims->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%$search%")
                    ->orWhereHas('transaction.user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', "%$search%");
                    });
            });
        }



        //date search

        if ($request->date) {

            $date = explode('-', $request->date);

            $request->merge([

                'start_date' => trim(@$date[0]),

                'end_date'  => trim(@$date[1])

            ]);

            $request->validate([

                'start_date'    => 'required|date_format:m/d/Y',

                'end_date'      => 'nullable|date_format:m/d/Y'

            ]);

            if ($request->end_date) {

                $endDate = Carbon::parse($request->end_date)->addHours(23)->addMinutes(59)->addSecond(59);

                $claims   = $claims->whereBetween('created_at', [Carbon::parse($request->start_date), $endDate]);
            } else {

                $claims   = $claims->whereDate('created_at', Carbon::parse($request->start_date));
            }
        }




        return $claims->orderBy('id', 'desc')->paginate(getPaginate());
    }



    public function details($id)

    {

        $general = GeneralSetting::first();

        $claim = Claim::where('id', $id)->with(['issue', 'click'])->firstOrFail();

        $pageTitle = $claim->click->user->username . ' requested ' . showAmount(getCashback($claim->click, $claim->order_amount)) . ' ' . $general->cur_text;

        return view('admin.claim.detail', compact('pageTitle', 'claim'));
    }





    public function approve($id, Request $request)

    {

        $claim = Claim::where('id', $id)->firstOrFail();

        $request->validate([

            'cashback' => 'required|integer',


        ]);

        $newSiteTransaction = new Transaction;
        $newSiteTransaction->user_id = $claim->click->user_id;
        $newSiteTransaction->amount = $request->cashback;
        $newSiteTransaction->type = 5;
        $newSiteTransaction->cashbacktype_id = $claim->click->model->cashbacktype_id;
        $newSiteTransaction->status = 1;
        $newSiteTransaction->save();
        $clickTransaction = new ClickTransaction;
        $clickTransaction->click_id = $claim->click->id;
        $clickTransaction->transaction_id = $newSiteTransaction->id;
        $clickTransaction->save();
        $claim->status = 2;
        $claim->save();
        notify($claim->click->user, 'CLAIM_APPROVE', [

            'cashback_amount' => $newSiteTransaction->amount,

            'claim_id' => $claim->id,



        ]);


        $notify[] = ['success', 'Claim request approved successfully'];
        return to_route('admin.claims.pending')->withNotify($notify);
    }



    public function reject(Request $request)

    {

        $request->validate([

            'id' => 'required|integer',

            'message' => 'required|string|max:255'

        ]);

        $claim = Claim::where('id', $request->id)->firstOrFail();

        $claim->status = 2;

        $claim->save();




        notify($transaction->user, 'CLAIM_REJECT', [


            'cashback_amount' => getCashback($claim->click, $claim->order_amount),

            'claim_id' => $claim->id,

            'rejection_message' => $request->message

        ]);



        $notify[] = ['success', 'Claim request rejected successfully'];

        return  to_route('admin.claim.pending')->withNotify($notify);
    }
}
