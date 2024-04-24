<?php



namespace App\Http\Controllers\User;



use App\Http\Controllers\Controller;

use App\Models\GeneralSetting;

use App\Models\Coupon;

use App\Models\Store;

use App\Models\Referral;

use App\Models\Transaction;

use App\Models\User;

use App\Models\SupportTicket;

use App\Models\Click;

use Illuminate\Http\Request;



class UserController extends Controller

{

    public function home()

    {

        $pageTitle = 'Dashboard';

        

        $general = GeneralSetting::first();

        $widget['confirmed_balance'] = auth()->user()->balance;

        $widget['pending_balance'] = auth()->user()->pendingBalance;

        $widget['cancelled_balance'] = auth()->user()->cancelledBalance;

        $widget['confirmed_points'] = auth()->user()->points;

        $widget['pending_points'] = auth()->user()->pendingPoints;

        $widget['cancelled_points'] = auth()->user()->cancelledPoints;
        
        $widget['pending_referral'] = Referral::pending()->where('referrer_id', auth()->id())->count();

        $widget['confirmed_referral'] = Referral::confirmed()->where('referrer_id', auth()->id())->count();

        $widget['cancelled_referral'] = Referral::cancelled()->where('referrer_id', auth()->id())->count();


        $transactions = Transaction::where('user_id', auth()->id())->where('type', '<>', 6)->latest()->limit(5)->get();
        $clicks = Click::where('user_id',auth()->user()->id)->latest()->limit(5)->get();


        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'widget', 'transactions','general','clicks'));

    }


    public function referrals()

    {

        $pageTitle = 'Referrals';

        $general = GeneralSetting::first();

        $widget['pending_referral'] = Referral::pending()->where('referrer_id', auth()->id())->count();

        $widget['confirmed_referral'] = Referral::confirmed()->where('referrer_id', auth()->id())->count();

        $widget['cancelled_referral'] = Referral::cancelled()->where('referrer_id', auth()->id())->count();

        $referrer_credit = (int)$general->referrer_credit;
        $referral_credit = (int)$general->referral_credit;
        $referral_days = (int)$general->referral_days;
        $referral_min = (int)$general->referral_min;
        $cur_sym = $general->cur_sym;


        $referrals = Referral::where('referrer_id', auth()->id())->latest()->limit(10)->get();


        return view($this->activeTemplate . 'user.referrals', compact('pageTitle', 'widget', 'referrals','referrer_credit','referral_credit','referral_days','referral_min','cur_sym'));

    }



    public function favoriteStores()
    {
        $pageTitle = 'Favorite Stores';
        $user =  auth()->user();
    
        $stores = Store::where('status', 1)
            ->whereHas('countries', function ($query) use ($user) {
                $query->where('country_code', $user->country_code)->orWhere('country_code', 'W');
            })
            ->whereIn('id', $user->favorite()->pluck('store_id'))
            ->with('coupons')
            ->latest()
            ->limit(10)
            ->paginate(getPaginate());
    
        return view($this->activeTemplate . 'user.favorite_stores', compact('pageTitle', 'stores'));
    }


    // public function depositHistory(Request $request)

    // {

    //     $pageTitle = 'Payment History';

    //     $deposits = auth()->user()->deposits();

    //     if ($request->search) {

    //         $deposits = $deposits->where('trx',$request->search);

    //     }

    //     $deposits = $deposits->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());

    //     return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'deposits'));

    // }


    public function withdrawHistory(){
        $pageTitle = 'Withdraw History';
        $withdraws = auth()->user()->withdraws();
        $withdraws = $withdraws->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.withdraw.history', compact('pageTitle', 'withdraws'));

    }

    public function attachmentDownload($fileHash)

    {

        $filePath = decrypt($fileHash);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $general = GeneralSetting::first();

        $title = slug($general->site_name).'- attachments.'.$extension;

        $mimetype = mime_content_type($filePath);

        header('Content-Disposition: attachment; filename="' . $title);

        header("Content-Type: " . $mimetype);

        return readfile($filePath);

    }



    public function userData()

    {

        $user = auth()->user();

        if ($user->reg_step == 1) {

            return to_route('user.home');

        }

        $pageTitle = 'User Data';

        return view($this->activeTemplate.'user.user_data', compact('pageTitle','user'));

    }



    public function userDataSubmit(Request $request)

    {

        $user = auth()->user();

        if ($user->reg_step == 1) {

            return to_route('user.home');

        }

        $request->validate([

            'firstname'=>'required|regex:/^[a-zA-Z]+$/',

            'lastname'=>'required|regex:/^[a-zA-Z]+$/',
            'city'=>'required|regex:/^[a-zA-Z]+$/',


        ]);

        $user->firstname = $request->firstname;

        $user->lastname = $request->lastname;

        $user->address = [

            'country'=>@$user->address->country,

            'address'=>$request->address,

            'state'=>$request->state,

            'zip'=>$request->zip,

            'city'=>$request->city,

        ];

        $user->reg_step = 1;

        $user->save();



        $notify[] = ['success','Registration process completed successfully'];

        return to_route('user.home')->withNotify($notify);



    }



}