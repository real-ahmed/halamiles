<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Referral;

class ReferralController extends Controller
{
    public function allReferral()
    {
        $pageTitle = 'My All Referral';
        $referrals  = Referral::where('referrer_id', auth()->id())->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.referral.index', compact('pageTitle', 'referrals'));
    }

    public function pendingReferral()
    {
        $pageTitle = 'My Pending Referral';
        $referrals = Referral::pending()->where('referrer_id', auth()->id())->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.referral.index', compact('pageTitle', 'referrals'));
    }

    public function confirmedReferral()
    {
        $pageTitle = 'My Confirmed Referral';
        $referrals = Referral::confirmed()->where('referrer_id', auth()->id())->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.referral.index', compact('pageTitle', 'referrals'));
    }

    public function cancelledReferral()
    {
        $pageTitle = 'My Cancelled Referral';
        $referrals = Referral::cancelled()->where('referrer_id', auth()->id())->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.referral.index', compact('pageTitle', 'referrals'));
    }
}
