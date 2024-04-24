<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;
use App\Models\Transaction;
use Carbon\Carbon;

class ManageReferralsController extends Controller
{
    public function all()

    {

        $pageTitle = 'Referrals';

        $referralData = $this->referralData($scope = null, $summary = true);

        $referrals = $referralData['data'];

        $summery = $referralData['summary'];


        $total = $summery['total'];

        $confirmed = $summery['confirmed'];

        $pending = $summery['pending'];

        $cancelled = $summery['cancelled'];

        return view('admin.referral.log', compact('pageTitle', 'referrals','total','confirmed','pending','cancelled'));

    }

    public function confirmed()

    {

        $pageTitle = 'Confirmed Referrals';

        $referrals = $this->referralData('confirmed');


        return view('admin.referral.log', compact('pageTitle', 'referrals'));

    }


    public function pending()

    {

        $pageTitle = 'Pending Referrals';

        $referrals = $this->referralData('pending');


        return view('admin.referral.log', compact('pageTitle', 'referrals'));

    }


    public function cancelled()

    {

        $pageTitle = 'Cancelled Referrals';

        $referrals = $this->referralData('cancelled');


        return view('admin.referral.log', compact('pageTitle', 'referrals'));

    }





    protected function referralData($scope = null, $summary = false)
    {
        if ($scope) {
            $referrals = Referral::$scope()->with(['user', 'referrer']);
        } else {
            $referrals = Referral::with(['user', 'referrer']);
        }
    
        $request = request();
    
        // Search
        if ($request->search) {
            $search = $request->search;
            $referrals = $referrals->where(function ($q) use ($search) {
                $q->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                })->orWhereHas('referrer', function ($referrer) use ($search) {
                    $referrer->where('username', 'like', "%$search%");
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
                $referrals = $referrals->whereBetween('created_at', [Carbon::parse($request->start_date), $endDate]);
            } else {
                $referrals = $referrals->whereDate('created_at', Carbon::parse($request->start_date));
            }
        }
    

    
        if (!$summary) {
            return $referrals->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $pending = clone $referrals;
            $confirmed = clone $referrals;
            $total = clone $referrals;
            $cancelled = clone $referrals;
    
            $pendingSummary = $pending->whereHas('userTransaction', function ($query) {
                $query->where('status', 0);
            })->count();
    
            $confirmedSummary = $confirmed->whereHas('userTransaction', function ($query) {
                $query->where('status', 1);
            })->count();

            $totalSummary = $total->whereHas('userTransaction', function ($query) {
                $query;
            })->count();
    
            $cancelledSummary = $cancelled->whereHas('userTransaction', function ($query) {
                $query->where('status', 2);
            })->count();
    
            return [
                'data' => $referrals->orderBy('id', 'desc')->paginate(getPaginate()),
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
