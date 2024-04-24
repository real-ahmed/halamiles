<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use App\Models\Referral;
use App\Models\GeneralSetting;

class ManageTransactionsController extends Controller
{
    public function all()
    {

        $pageTitle = 'Transactions';

        $transactionData = $this->transactionData($scope = null, $summary = true);

        $transactions = $transactionData['data'];

        $summery = $transactionData['summary'];


        $total = $summery['total'];

        $confirmed = $summery['confirmed'];

        $pending = $summery['pending'];

        $cancelled = $summery['cancelled'];

        return view('admin.transaction.log', compact('pageTitle', 'transactions','total','confirmed','pending','cancelled'));

    }

    public function confirmed()
    {

        $pageTitle = 'Confirmed Transactions';

        $transactions = $this->transactionData('confirmed');


        return view('admin.transaction.log', compact('pageTitle', 'transactions'));

    }


    public function pending()
    {

        $pageTitle = 'Pending Transactions';

        $transactions = $this->transactionData('pending');


        return view('admin.transaction.log', compact('pageTitle', 'transactions'));

    }


    public function cancelled()
    {

        $pageTitle = 'Cancelled Transactions';

        $transactions = $this->transactionData('cancelled');


        return view('admin.transaction.log', compact('pageTitle', 'transactions'));

    }


    public function details($id){
        $pageTitle = 'Transaction - '.$id;
        $transaction = Transaction::find($id);
        $general = GeneralSetting::first();
        return view('admin.transaction.detail', compact('pageTitle', 'transaction','general'));

    }


    protected function transactionData($scope = null, $summary = false)
    {
        // Create the base query for transactions
        if ($scope) {
            $transactions = Transaction::$scope()->with(['user', 'cashback']);
        } else {
            $transactions = Transaction::with(['user', 'cashback']);
        }

        $request = request();

        // Search
        if ($request->search) {
            $search = $request->search;
            $transactions = $transactions->where(function ($q) use ($search) {
                $q->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                });
            });
        }

        // Date search
        if ($request->date) {
            $date = explode('-', $request->date);
            $request->merge([
                'start_date' => trim(@$date[0]),
                'end_date' => trim(@$date[1]),
            ]);
            $request->validate([
                'start_date' => 'required|date_format:m/d/Y',
                'end_date' => 'nullable|date_format:m/d/Y',
            ]);

            if ($request->end_date) {
                $endDate = Carbon::parse($request->end_date)->addHours(23)->addMinutes(59)->addSecond(59);
                $transactions = $transactions->whereBetween('created_at', [Carbon::parse($request->start_date), $endDate]);
            } else {
                $transactions = $transactions->whereDate('created_at', Carbon::parse($request->start_date));
            }
        }

        if (!$summary) {
            return $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $pending = clone $transactions;
            $confirmed = clone $transactions;
            $total = clone $transactions;
            $cancelled = clone $transactions;

            $pendingSummary = $pending->where('status', 0)->count();
            $confirmedSummary = $confirmed->where('status', 1)->count();
            $totalSummary = $total->count();
            $cancelledSummary = $cancelled->where('status', 2)->count();

            return [
                'data' => $transactions->orderBy('id', 'desc')->paginate(getPaginate()),
                'summary' => [
                    'total' => $totalSummary,
                    'confirmed' => $confirmedSummary,
                    'pending' => $pendingSummary,
                    'cancelled' => $cancelledSummary,
                ],
            ];
        }
    }

}
