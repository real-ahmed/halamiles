<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\GeneralSetting;
use App\Models\Coupon;
use App\Models\Store;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Validation\Rules\Password;

class ManageUsersController extends Controller
{

    public function allUsers()
    {
        $pageTitle = 'All Users';
        $users = $this->userData();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }

    public function usersByStore($storeId)
    {
        $pageTitle = 'Users';
        $store = Store::findOrFail($storeId);
        $favoriteUserIds = $store->favorite->pluck('user_id')->toArray();
        $users = User::whereIn('id', $favoriteUserIds)->paginate(getPaginate());
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }


    public function activeUsers()
    {
        $pageTitle = 'Active Users';
        $users = $this->userData('active');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }

    public function bannedUsers()
    {
        $pageTitle = 'Banned Users';
        $users = $this->userData('banned');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = 'Email Unverified Users';
        $users = $this->userData('emailUnverified');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }


    public function emailVerifiedUsers()
    {
        $pageTitle = 'Email Verified Users';
        $users = $this->userData('emailVerified');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }


    public function mobileUnverifiedUsers()
    {
        $pageTitle = 'Mobile Unverified Users';
        $users = $this->userData('mobileUnverified');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }


    public function mobileVerifiedUsers()
    {
        $pageTitle = 'Mobile Verified Users';
        $users = $this->userData('mobileVerified');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.list', compact('pageTitle', 'users', 'countries'));
    }


    protected function userData($scope = null)
    {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }

        //search
        $request = request();
        if ($request->search) {
            $search = $request->search;
            $users  = $users->where(function ($user) use ($search) {
                $user->where('username', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        $sortBy = request()->input('sort_by', 'created_at');
        $sortDirection = request()->input('sort_direction', 'desc');
        return $users->orderBy($sortBy, $sortDirection)->paginate(getPaginate());
    }

    public function detail($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Detail - ' . $user->username;

        $totalDeposit = Deposit::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $totalCoupon = Coupon::where('user_id', $user->id)->count();
        $activeCoupon = Coupon::active()->where('user_id', $user->id)->count();
        $totalStore = Store::where('user_id', $user->id)->count();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.detail', compact('pageTitle', 'user', 'totalDeposit', 'totalCoupon', 'activeCoupon', 'totalStore', 'countries'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray   = (array)$countryData;
        $countries      = implode(',', array_keys($countryArray));

        $countryCode    = $request->country;
        $country        = $countryData->$countryCode->country;
        $dialCode       = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:40|unique:users,mobile,' . $user->id,
            'country' => 'required|in:' . $countries,
        ]);
        $user->mobile = $dialCode . $request->mobile;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->address = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$country,
        ];
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->latest_news = $request->latest_news ? 1 : 0;
        $note = new Note();
        $note->note = $request->note;
        $user->notes()->save($note);
        $user->save();

        $notify[] = ['success', 'User details updated successfully'];
        return redirect()->back()->withNotify($notify);
    }


    public function create(Request $request)
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country = $countryData->$countryCode->country;
        $dialCode = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users',
            'username' => 'required|alpha_num|unique:users|min:6',
            'mobile' => 'required|string|max:40|unique:users',
            'country' => 'required|in:' . $countries,
            'password' => 'required|confirmed|min:6',
        ]);

        $user = new User();
        $user->mobile = $dialCode . $request->mobile;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = strtolower(trim($request->username));
        $user->password = bcrypt($request->password);
        $user->address = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$country,
        ];
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->latest_news = $request->latest_news ? 1 : 0;
        $user->save();

        if ($request->gift) {
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $general = GeneralSetting::first();
            $transaction->amount = $general->gift_credit;
            $transaction->type = 1;
            $transaction->save();
        }

        $notify[] = ['success', 'User created successfully'];
        return redirect()->back()->withNotify($notify);
    }



    public function addBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);
        $user = User::find($id);
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $request->amount;
        $transaction->type = 0;
        $transaction->status = 1;
        $transaction->save();
        $note = new Note();
        $note->note = $request->note;
        $transaction->note()->save($note);
        $transaction->save();
        $notify[] = ['success', 'Balance added successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::find($id);

        if (!$user) {
            $notify[] = ['error', 'User not found'];
            return redirect()->back()->withNotify($notify);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        $userIpInfo = getIpInfo();
        $userBrowser = osBrowser();

        notify($user, 'PASS_RESET_DONE', [
            'operating_system' => @$userBrowser['os_platform'],
            'browser' => @$userBrowser['browser'],
            'ip' => @$userIpInfo['ip'],
            'time' => @$userIpInfo['time']
        ], ['email']);

        $notify[] = ['success', 'Password changed successfully'];
        return redirect()->back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == 1) {
            $request->validate([
                'reason' => 'required|string|max:255',
                'ban_note' => 'required|string|max:255'
            ]);
            $user->status = 0;
            $user->ban_reason = $request->reason;
            $user->ban_note = $request->ban_note;
            $notify[] = ['success', 'User banned successfully'];
        } else {
            $user->status = 1;
            $user->ban_reason = null;
            $notify[] = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);
    }

    public function sendVerifyCode($type, $id)
    {
        $user = User::findOrFail($id);
        $user->ver_code = verificationCode(6);
        $user->ver_code_send_at = Carbon::now();
        $user->save();

        if ($type == 'email') {
            $notifyTemplate = 'EVER_CODE';
        } else {
            $notifyTemplate = 'SVER_CODE';
        }

        notify($user, $notifyTemplate, [
            'code' => $user->ver_code
        ], [$type]);

        $notify[] = ['success', 'Verification code sent successfully'];
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        $general = GeneralSetting::first();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = GeneralSetting::first();

        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return redirect()->route('admin.dashboard')->withNotify($notify);
        }

        // Get a list of users to populate the select input in your form.
        // Depending on your application's size, you may need to paginate or 
        // otherwise limit this selection.
        $users  = User::where('ev', 1)
            ->where('sv', 1)
            ->where('status', 1)
            ->get(['id', 'email']); // get only the id and name columns for performance

        $usersCount = $users->count();

        $usersNewsCount = $users->where('latest_news', 1)->count();
        $pageTitle = 'Notification to Verified Users';

        return view('admin.users.notification_all', compact('pageTitle', 'usersCount', 'usersNewsCount', 'users'));
    }


    public function sendNotificationAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $user = User::where('status', 1)->where('ev', 1)->where('sv', 1)->skip($request->skip)->first();

        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => 'message sent',
            'total_sent' => $request->skip + 1,
        ]);
    }
    public function sendToSelected(Request $request)
    {
        // Validate the request data...
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'selected_users' => 'required|array',
        ]);

        // Fetch selected users and send them an email...
        $users = User::find($request->selected_users);

        foreach ($users as $user) {
            // Send email to user...
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
        }

        // Return a response...
        return response()->json(['total_sent' => count($users)]);
    }

    public function sendToLatestNews(Request $request)
    {
        // Validate the request data...
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Fetch users with the latest news and send them an email...
        $users = User::where('latest_news', 1)->get(); // Assuming you have a field 'has_latest_news'.

        foreach ($users as $user) {
            // Send email to user...
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
        }

        // Return a response...
        return response()->json(['total_sent' => count($users)]);
    }
    public function notificationLog($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $user->username;
        $logs = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'user'));
    }
}