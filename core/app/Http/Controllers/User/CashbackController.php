<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\GeneralSetting;
use App\Models\Package;
use App\Models\Transaction;
use App\Models\Store;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class CashbackController extends Controller
{
    public function allCashbacks()
    {
        $pageTitle = 'My All Cashbacks';
        
        $transactions  = Transaction::where('user_id', auth()->id())->where('type', '<>', 6)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }

    public function pendingCashbacks()
    {
        $pageTitle = 'My Pending Cashbacks';
        $transactions = Transaction::pending()->where('user_id', auth()->id())->where('type', '<>', 6)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }

    public function confirmedCashbacks()
    {
        $pageTitle = 'My Active Cashbacks';
        $transactions = Transaction::confirmed()->where('user_id', auth()->id())->where('type', '<>', 6)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }

    public function cancelledCashbacks()
    {
        $pageTitle = 'My Expired Cashbacks';
        $transactions = Transaction::cancelled()->where('user_id', auth()->id())->where('type', '<>', 6)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }



    public function pendingPoints()
    {
        $pageTitle = 'My Pending Cashbacks';
        $transactions = Transaction::pending()->where('user_id', auth()->id())->where('type', '<>', 6)->where('cashbacktype_id',3)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }

    public function confirmedPoints()
    {
        $pageTitle = 'My Active Cashbacks';
        $transactions = Transaction::confirmed()->where('user_id', auth()->id())->where('type', '<>', 6)->where('cashbacktype_id',3)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }

    public function cancelledPoints()
    {
        $pageTitle = 'My Expired Cashbacks';
        $transactions = Transaction::cancelled()->where('user_id', auth()->id())->where('type', '<>', 6)->where('cashbacktype_id',3)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.cashback.index', compact('pageTitle', 'transactions'));
    }

}
