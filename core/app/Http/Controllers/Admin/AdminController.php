<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;

use App\Lib\CurlRequest;

use App\Models\AdminNotification;

use App\Models\Deposit;

use App\Models\Withdrawal;

use App\Models\GeneralSetting;

use App\Models\Package;

use App\Models\Coupon;

use App\Models\Store;

use App\Models\User;

use App\Models\UserLogin;

use App\Rules\FileTypeValidate;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class AdminController extends Controller

{



    public function dashboard()

    {



        $pageTitle = 'Dashboard';



        // User Info

        $widget['total_users']             = User::count();

        $widget['verified_users']          = User::where('status', 1)->where('ev',1)->where('sv',1)->count();

        $widget['email_unverified_users']  = User::emailUnverified()->count();

        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();





        // user Browsing, Country, Operating Log

        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDay(30))->get(['browser', 'os', 'country']);



        $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map(function ($item, $key) {

            return collect($item)->count();

        });

        $chart['user_os_counter'] = $userLoginData->groupBy('os')->map(function ($item, $key) {

            return collect($item)->count();

        });

        $chart['user_country_counter'] = $userLoginData->groupBy('country')->map(function ($item, $key) {

            return collect($item)->count();

        })->sort()->reverse()->take(5);





        $deposit['total_deposit_amount']        = Withdrawal::approved()->with('transaction')->get()->sum(function($withdrawal) {
                                                        return $withdrawal->transaction ? $withdrawal->transaction->amount : 0;
                                                    });
        $deposit['total_deposit_pending']       = Withdrawal::pending()->count();

        $deposit['total_deposit_rejected']      = Withdrawal::rejected()->count();

        $deposit['total_deposit_charge']        = Withdrawal::approved()->with('transaction')->get()->sum(function($withdrawal) {
                                                        return $withdrawal->transaction ? $withdrawal->transaction->charge : 0;
                                                    });



        $widget['total_package'] = Package::count();

        $widget['total_coupon'] = Coupon::count();

        $widget['active_coupon'] = Coupon::active()->count();

        $widget['total_store'] = Store::count();





        // Monthly Deposit Report Graph

        $report['months'] = collect([]);

        $report['deposit_month_amount'] = collect([]);



        $depositsMonth = Deposit::where('created_at', '>=', Carbon::now()->subYear())

    ->where('status', 1)

    ->selectRaw("SUM(CASE WHEN status = 1 THEN amount END) as depositAmount")

    ->selectRaw("DATE_FORMAT(created_at, '%M-%Y') as months")

    ->groupBy('months')

    ->orderByRaw("MIN(created_at) ASC")

    ->get();



$depositsMonth->map(function ($depositData) use ($report) {

    $report['months']->push($depositData->months);

    $report['deposit_month_amount']->push(getAmount($depositData->depositAmount));

});



        



        $months = $report['months'];



        for($i = 0; $i < $months->count(); ++$i) {

            $monthVal      = Carbon::parse($months[$i]);

            if(isset($months[$i+1])){

                $monthValNext = Carbon::parse($months[$i+1]);

                if($monthValNext < $monthVal){

                    $temp = $months[$i];

                    $months[$i]   = Carbon::parse($months[$i+1])->format('F-Y');

                    $months[$i+1] = Carbon::parse($temp)->format('F-Y');

                }else{

                    $months[$i]   = Carbon::parse($months[$i])->format('F-Y');

                }

            }

        }

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart','deposit','report','depositsMonth','months'));

    }





    public function profile()

    {

        $pageTitle = 'Profile';

        $admin = auth('admin')->user();

        return view('admin.profile', compact('pageTitle', 'admin'));

    }



    public function profileUpdate(Request $request)

    {

        $this->validate($request, [

            'name' => 'required',

            'email' => 'required|email',

            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]

        ]);

        $user = auth('admin')->user();



        if ($request->hasFile('image')) {

            try {

                $old = $user->image;

                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);

            } catch (\Exception $exp) {

                $notify[] = ['error', 'Couldn\'t upload your image'];

                return back()->withNotify($notify);

            }

        }



        $user->name = $request->name;

        $user->email = $request->email;

        $user->save();

        $notify[] = ['success', 'Profile updated successfully'];

        return to_route('admin.profile')->withNotify($notify);

    }





    public function password()

    {

        $pageTitle = 'Password Setting';

        $admin = auth('admin')->user();

        return view('admin.password', compact('pageTitle', 'admin'));

    }



    public function passwordUpdate(Request $request)

    {

        $this->validate($request, [

            'old_password' => 'required',

            'password' => 'required|min:5|confirmed',

        ]);



        $user = auth('admin')->user();

        if (!Hash::check($request->old_password, $user->password)) {

            $notify[] = ['error', 'Password doesn\'t match!!'];

            return back()->withNotify($notify);

        }

        $user->password = bcrypt($request->password);

        $user->save();

        $notify[] = ['success', 'Password changed successfully.'];

        return to_route('admin.password')->withNotify($notify);

    }



    public function notifications(){

        $notifications = AdminNotification::orderBy('id','desc')->with('user')->paginate(getPaginate());

        $pageTitle = 'Notifications';

        return view('admin.notifications',compact('pageTitle','notifications'));

    }





    public function notificationRead($id){

        $notification = AdminNotification::findOrFail($id);

        $notification->read_status = 1;

        $notification->save();

        $url = $notification->click_url;

        if ($url == '#') {

            $url = url()->previous();

        }

        return redirect($url);

    }



    public function requestReport()

    {

        $pageTitle = 'Your Listed Report & Request';

        $arr['app_name'] = systemDetails()['name'];

        $arr['app_url'] = env('APP_URL');

        $arr['purchase_code'] = env('PURCHASE_CODE');

        $url = "https://license.viserlab.com/issue/get?".http_build_query($arr);

        $response = CurlRequest::curlContent($url);

        $response = json_decode($response);

        if ($response->status == 'error') {

            return to_route('admin.dashboard')->withErrors($response->message);

        }

        $reports = $response->message[0];

        return view('admin.reports',compact('reports','pageTitle'));

    }



    public function reportSubmit(Request $request)

    {

        $request->validate([

            'type'=>'required|in:bug,feature',

            'message'=>'required',

        ]);

        $url = 'https://license.viserlab.com/issue/add';



        $arr['app_name'] = systemDetails()['name'];

        $arr['app_url'] = env('APP_URL');

        $arr['purchase_code'] = env('PURCHASE_CODE');

        $arr['req_type'] = $request->type;

        $arr['message'] = $request->message;

        $response = CurlRequest::curlPostContent($url,$arr);

        $response = json_decode($response);

        if ($response->status == 'error') {

            return back()->withErrors($response->message);

        }

        $notify[] = ['success',$response->message];

        return back()->withNotify($notify);

    }



    public function readAll(){

        AdminNotification::where('read_status',0)->update([

            'read_status'=>1

        ]);

        $notify[] = ['success','Notifications read successfully'];

        return back()->withNotify($notify);

    }



    public function downloadAttachment($fileHash)

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

    

public function download($table, $columns)

{

    // Convert the columns string to an array

    $columnsArray = explode(',', $columns);



    // Retrieve data from the database using the provided table name and columns

    $data = \DB::table($table)->select($columnsArray)->get();



    // Generate the CSV content

    $csvData = $this->generateCsvData($data, $columnsArray);



    // Create a response with the CSV file content

    $response = response($csvData);

    $response->header('Content-Type', 'text/csv');

    $response->header('Content-Disposition', 'attachment; filename="data.csv"');



    return $response;

}



private function generateCsvData($data, $columns)

{

    $output = fopen('php://temp', 'w');



    // Write the header row

    fputcsv($output, $columns);



    // Write the data rows

    foreach ($data as $row) {

        $rowData = [];

        foreach ($columns as $column) {

            $rowData[] = $row->$column;

        }

        fputcsv($output, $rowData);

    }



    rewind($output);

    $csvData = stream_get_contents($output);

    fclose($output);



    return $csvData;

}





}

