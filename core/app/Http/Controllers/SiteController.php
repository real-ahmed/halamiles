<?php



namespace App\Http\Controllers;

use App\Models\AdminNotification;

use App\Models\Advertisement;

use App\Models\Category;

use App\Models\Frontend;

use App\Models\GeneralSetting;

use App\Models\Language;

use App\Models\Page;

use App\Models\Coupon;

use App\Models\Product;

use App\Models\StoresCategory;

use App\Models\CouponReport;

use App\Models\Store;

use App\Models\User;

use App\Models\Click;

use App\Models\Favorite;

use App\Models\SupportMessage;

use App\Models\SupportTicket;

use Carbon\Carbon;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cookie;



class SiteController extends Controller

{

    public function index()
    {

        $pageTitle = 'Home';

        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', '/')->first();

        return view($this->activeTemplate . 'home', compact('pageTitle', 'sections'));
    }



    public function pages($slug)

    {

        $page = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();

        $pageTitle = $page->name;

        $sections = $page->secs;

        return view($this->activeTemplate . 'pages', compact('pageTitle', 'sections'));
    }



    public function search(Request $request, $filterType = null, $filterId = 0)

    {

        $pageTitle = 'Coupon List';

        $categories = Category::where('status', 1)->get();

        $coupons = Coupon::active();

        $user = auth()->user();
        $user_id = isset($user['id']) ? $user['id'] : 0;

        if ($user_id !== 0) {
            $userCountry = $user['country_code'];

            $coupons = $coupons->whereHas('countries', function ($query) use ($userCountry) {
                $query->where('country_code', $userCountry)
                    ->orWhere('country_code', 'W');
            });
        }



        $searchKey = $request->search_key;



        if ($searchKey) {

            $coupons = $coupons->where(function ($query) use ($searchKey) {

                $query->where('title', 'like', "%$searchKey%")

                    ->orWhereHas('category', function ($category) use ($searchKey) {

                        $category->where('name', 'like', "%$searchKey%");
                    })->orWhereHas('store', function ($store) use ($searchKey) {

                        $store->where('name', 'like', "%$searchKey%");
                    });
            });
        }



        if ($request->category) {

            $pageTitle = 'Coupons List by Category';

            $coupons = $coupons->whereIn('category_id', $request->category);
        }



        if ($filterType == 'category') {

            $coupons = $coupons->where('category_id', $filterId);

            $request->category = [$filterId];
        }



        if ($filterType == 'store') {

            $coupons = $coupons->where('store_id', $filterId);
        }



        if ($filterType == 'today-deal') {

            $pageTitle = 'Today\'s Deal';

            $coupons = $coupons->where('today_deal', 1);
        }



        if ($filterType == 'top-deal') {

            $pageTitle = 'Top Deal';

            $coupons = $coupons->where('top_deal', 1);
        }



        if ($filterType == 'expire-soon') {

            $pageTitle = 'Expire Soon';

            $coupons = $coupons->where('ending_date', '<=', now()->addDays(7))->orderBy('ending_date', 'ASC');
        }




        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');



        $coupons = $coupons->with('store')
            ->withCount(['reports' => function ($report) {
                $report->where('action', 'copy')->whereDate('date', now());
            }])
            ->orderBy($sortBy, $sortDirection)
            ->paginate(getPaginate(18));







        $pageTitle = $filterType == 'category' ? $coupons[0]->category->name . ' ' . $pageTitle : $pageTitle;

        $pageTitle = $filterType == 'store' ? $coupons[0]->store->name . ' ' . $pageTitle : $pageTitle;



        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'coupon')->first();



        return view($this->activeTemplate . 'coupon_list', compact('pageTitle', 'categories', 'coupons', 'sections'));
    }



    public function getStoresByCategory($category_id)
    {
        $user = auth()->user();
        $user_id = isset($user['id']) ? $user['id'] : 0;
        $USERCOUNTRY = $user->country_code;
        $stores = Store::where('category_id', $category_id)->where('status', 1)
            ->with([
                'coupons' => function ($query) {
                    $query->where('status', 1);
                }
            ])
            ->latest()
            ->limit(10)
            ->get()
            ->filter(
                function ($store) use ($USERCOUNTRY) {
                    $countries = $store->countries->pluck('country_code')->toArray();
                    return in_array($USERCOUNTRY, $countries) || in_array('W', $countries);
                }
            );


        $stores->transform(function ($store) {
            // Assume 'name' is the column that holds the store name
            $store->name = __($store->name);
            $cashbackInfo = __('Up To') . " " .
                $store->cashback .
                $store->cashbacktype->sign . " " .
                ($store->cashbacktype->id != 3 ? __('CashBack') : __('HalaMiles'));

            // Add cashback info to the store object
            $store->cashback_info = $cashbackInfo;
            return $store;
        });
        return response()->json($stores);
    }

    public function saveCouponView(Request $request)

    {
        $coupon = Coupon::findOrFail($request->couponId);
        $this->saveCouponClick($coupon, 0);
        return true;
    }

    public function saveCouponCopy(Request $request)
    {
        $coupon = Coupon::findOrFail($request->couponId);
        $this->saveCouponClick($coupon, 2);
        return true;
    }

    public static function saveCouponClick($coupon, $clickType)
    {
        $user = auth()->user();
        $click = new Click();
        $click->type = $clickType;
        $click->user_id = isset($user['id']) ? $user['id'] : 0;
        $click->ip = getRealIP();
        $coupon->clicks()->save($click);
        $coupon->save();
        return $click->id;
    }

    public static function saveStoreClick($store, $clickType)
    {
        $user = auth()->user();
        $click = new Click();
        $click->type = $clickType;
        $click->user_id = isset($user['id']) ? $user['id'] : 0;
        $click->ip = getRealIP();
        $store->clicks()->save($click);
        $store->save();
        return $click->id;
    }

    public static function saveCategoryClick($category, $clickType)
    {
        $user = auth()->user();
        $click = new Click();
        $click->type = $clickType;
        $click->user_id = isset($user['id']) ? $user['id'] : 0;
        $click->ip = getRealIP();
        $category->clicks()->save($click);
        $category->save();
        return $click->id;
    }

    public function popularStore()

    {

        $pageTitle = 'Popular Stores';

        $stores = Store::featured()

            ->where('status', 1)

            ->with('coupons')

            ->paginate(getPaginate());



        // Filter stores by the user's country code

        $user = auth()->user();
        $user_id = isset($user['id']) ? $user['id'] : 0;

        if ($user_id != 0) {
            $userCountry = $user['country_code'];
            $filteredStores = $stores->filter(function ($store) use ($userCountry) {
                $countries = $store->countries->pluck('country_code')->toArray();
                return in_array($userCountry, $countries) || in_array('W', $countries);
            });

            $filteredStoreIds = $filteredStores->pluck('id')->toArray();

            $stores = Store::whereIn('id', $filteredStoreIds)
                ->with('coupons')
                ->paginate(getPaginate());
        }
        $categories = Category::where('status', 1)->get();

        return view($this->activeTemplate . 'popular_stores', compact('pageTitle', 'stores', 'categories'));
    }



    public function stores(Request $request)
    {
        $pageTitle = 'Stores';
        $categories = Category::where('status', 1)->get();

        $stores = Store::where('status', 1)->with('coupons', 'category', 'countries');

        if ($request->has('category')) {
            $stores = $stores->where('category_id', $request->category);
        }

        if ($request->has('search_key')) {
            $searchKey = $request->search_key;
            $stores = $stores->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', "%$searchKey%")
                    ->orWhereHas('category', function ($category) use ($searchKey) {
                        $category->where('name', 'like', "%$searchKey%");
                    })->orWhereHas('coupons', function ($coupon) use ($searchKey) {
                        $coupon->where('title', 'like', "%$searchKey%");
                    });
            });
        }

        if (auth()->check()) {
            $userCountry = auth()->user()->country_code;
            $stores = $stores->whereHas('countries', function ($country) use ($userCountry) {
                $country->whereIn('country_code', [$userCountry, 'W']);
            });
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $stores = $stores->orderBy($sortBy, $sortDirection)
            ->paginate(getPaginate());

        return view($this->activeTemplate . 'popular_stores', compact('pageTitle', 'stores', 'categories'));
    }


    public function products(Request $request, $filterType = null)
    {
        $pageTitle = 'Products';
        $categories = Category::where('status', 1)->get();

        // Assuming 'status' is a field on the Product model, and
        // Product has relationships with 'category', 'countries', etc.
        $products = Product::where('status', 1)->with(['category', 'countries']);

        if ($request->has('category')) {
            $products = $products->where('category_id', $request->category);
        }

        if ($request->has('search_key')) {
            $searchKey = $request->search_key;
            $products = $products->where(function ($query) use ($searchKey) {

                $query->where('title', 'like', "%$searchKey%")

                    ->orWhereHas('category', function ($category) use ($searchKey) {

                        $category->where('name', 'like', "%$searchKey%");
                    })->orWhereHas('store', function ($store) use ($searchKey) {

                        $store->where('name', 'like', "%$searchKey%");
                    });
            });
        }

        if ($filterType == 'trend') {
            $products = $products->where('trend', 1);
        }

        // Assume that you've User's country and Product has a 'countries' relationship.
        if (auth()->check()) {
            $userCountry = auth()->user()->country_code;
            $products = $products->whereHas('countries', function ($country) use ($userCountry) {
                $country->whereIn('country_code', [$userCountry, 'W']);  // Note: Ensure the 'W' country_code or replace with a generic code if you have one.
            });
        }

        // Sorting and pagination logic remains the same unless you want to sort by a different default field.
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $products = $products->orderBy($sortBy, $sortDirection)
            ->paginate(getPaginate());  // Note: Ensure the getPaginate() function is available in your class.

        // Update the view to your appropriate view file.
        return view($this->activeTemplate . 'product_list', compact('pageTitle', 'products', 'categories'));
    }



    public function store($id)

    {

        $store = Store::where('id', $id)->first();

        $pageTitle = $store->name;

        $coupons = Coupon::active();

        $coupons = $coupons->where('store_id', $id);

        $category = Category::where('id', $store->category_id)->where('status', 1)->first();


        $this->saveStoreClick($store, 0);





        $coupons = $coupons->with('store')->withCount(['reports' => function ($report) {

            $report->where('action', 'copy')->where('date', 'like', now()->format('Y-m-d'));
        }])->orderBy('featured_validity', 'DESC')->paginate(getPaginate(18));






        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'coupon')->first();



        $user = auth()->user();



        try {

            $USERID = $user['id'];

            $USERCOUNTRY = $user['country_code'];
        } catch (\Exception $e) {

            $USERID = 0;

            $USERCOUNTRY = null;
        }

        $isfav = Favorite::where('user_id', $USERID)->where('store_id', $store->id)->exists();

        $countries = $store->countries->toArray();

        if ($store->status != 1) {
            return  view($this->activeTemplate . 'store_not_available', compact('pageTitle'));
        }

        if (in_array($USERCOUNTRY, array_column($countries, 'country_code')) || in_array('W', array_column($countries, 'country_code')) || $USERID == 0) {

            return view($this->activeTemplate . 'store', compact('pageTitle', 'store', 'coupons', 'sections', 'USERID', 'category', 'isfav'));
        } else {

            return view($this->activeTemplate . 'store_not_available', compact('pageTitle'));
        }
    }



    public function savefavorite(Request $request)

    {

        $storeID = $request->input('store_id');

        $user = auth()->user();

        $userID = $user['id'];





        $favorite = Favorite::where('user_id', $userID)->where('store_id', $storeID)->first();



        if ($favorite) {

            $favorite->delete();
        } else {

            Favorite::create([

                'user_id' => $userID,

                'store_id' => $storeID

            ]);
        }



        return response()->json(['message' => 'Favorite updated successfully']);
    }



    public function contact()

    {

        $pageTitle = "Contact Us";

        $categories = Category::where('status', 1)->get();

        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'contact')->first();



        return view($this->activeTemplate . 'contact', compact('pageTitle', 'categories', 'sections'));
    }





    public function contactSubmit(Request $request)

    {

        $this->validate($request, [

            'name' => 'required',

            'email' => 'required',

            'subject' => 'required|string|max:255',

            'message' => 'required',

        ]);



        if (!verifyCaptcha()) {

            $notify[] = ['error', 'Invalid captcha provided'];

            return back()->withNotify($notify);
        }



        $request->session()->regenerateToken();



        $random = getNumber();



        $ticket = new SupportTicket();

        $ticket->user_id = auth()->id() ?? 0;

        $ticket->name = $request->name;

        $ticket->email = $request->email;

        $ticket->priority = 2;





        $ticket->ticket = $random;

        $ticket->subject = $request->subject;

        $ticket->last_reply = Carbon::now();

        $ticket->status = 0;

        $ticket->save();



        $adminNotification = new AdminNotification();

        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;

        $adminNotification->title = 'A new support ticket has opened ';

        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);

        $adminNotification->save();



        $message = new SupportMessage();

        $message->support_ticket_id = $ticket->id;

        $message->message = $request->message;

        $message->save();



        $notify[] = ['success', 'Message sent successfully!'];



        return to_route('home')->withNotify($notify);
    }



    public function policyPages($slug, $id)

    {

        $policy = Frontend::where('id', $id)->firstOrFail();

        $pageTitle = $policy->data_values->title;

        return view($this->activeTemplate . 'policy', compact('policy', 'pageTitle'));
    }



    public function changeLanguage($lang = null)

    {

        $language = Language::where('code', $lang)->first();

        if (!$language) $lang = 'en';

        session()->put('lang', $lang);

        return redirect()->back();
    }



    public function adRedirect($id)
    {

        $id = decrypt($id);

        $ad = Advertisement::findOrFail($id);

        $ad->click += 1;

        $ad->save();

        if ($ad->type == 'image') {

            return redirect($ad->redirect_url);
        }

        return back();
    }



    public function blogs()

    {

        $pageTitle      = 'Blog Posts';

        $blogs          = Frontend::where('data_keys', 'blog.element')->orderBy('id', 'desc')->paginate(getPaginate(18));

        $sections       = Page::where('tempname', $this->activeTemplate)->where('slug', 'blog')->first();



        return view($this->activeTemplate . 'blogs', compact('pageTitle', 'blogs', 'sections'));
    }



    public function blogDetails($slug, $id)

    {

        $pageTitle = 'Blog Details';

        $blog = Frontend::where('id', $id)->where('data_keys', 'blog.element')->firstOrFail();

        $recentBlogs = Frontend::where('data_keys', 'blog.element')->where('id', '!=', $blog->id)->orderBy('id', 'desc')->limit(10)->get();



        $seoContents['keywords']           = $blog->meta_keywords ?? [];

        $seoContents['social_title']       = $blog->data_values->title;

        $seoContents['description']        = strLimit(strip_tags($blog->data_values->description), 150);

        $seoContents['social_description'] = strLimit(strip_tags($blog->data_values->description), 150);

        $seoContents['image']              = getImage('assets/images/frontend/blog/' . $blog->data_values->blog_image);

        $seoContents['image_size']         = '850x850';



        return view($this->activeTemplate . 'blog_details', compact('blog', 'pageTitle', 'recentBlogs', 'seoContents'));
    }





    public function cookieAccept()
    {

        $general = GeneralSetting::first();

        Cookie::queue('gdpr_cookie', $general->site_name, 43200);

        return back();
    }



    public function cookiePolicy()
    {

        $pageTitle = 'Cookie Policy';

        $cookie = Frontend::where('data_keys', 'cookie.data')->first();

        return view($this->activeTemplate . 'cookie', compact('pageTitle', 'cookie'));
    }



    public function placeholderImage($size = null)
    {

        $imgWidth = explode('x', $size)[0];

        $imgHeight = explode('x', $size)[1];

        $text = $imgWidth . '×' . $imgHeight;

        $fontFile = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';

        $fontSize = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) {

            $fontSize = 9;
        }

        if ($imgHeight < 100 && $fontSize > 30) {

            $fontSize = 30;
        }



        $image     = imagecreatetruecolor($imgWidth, $imgHeight);

        $colorFill = imagecolorallocate($image, 100, 100, 100);

        $bgFill    = imagecolorallocate($image, 175, 175, 175);

        imagefill($image, 0, 0, $bgFill);

        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);

        $textWidth  = abs($textBox[4] - $textBox[0]);

        $textHeight = abs($textBox[5] - $textBox[1]);

        $textX      = ($imgWidth - $textWidth) / 2;

        $textY      = ($imgHeight + $textHeight) / 2;

        header('Content-Type: image/jpeg');

        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);

        imagejpeg($image);

        imagedestroy($image);
    }



    public function maintenance()

    {

        $pageTitle = 'Maintenance Mode';

        $general = GeneralSetting::first();

        if ($general->maintenance_mode == 0) {

            return to_route('home');
        }

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();

        return view($this->activeTemplate . 'maintenance', compact('pageTitle', 'maintenance'));
    }

    public function redirectToStore($storeId)
    {
        $store = Store::findOrFail($storeId);
        $click = $this->saveStoreClick($store, 1);
        $url = str_replace('{USERID}', $click, $store->url);
        $img = getImage(getFilePath('store') . '/' . $store->image);
        return view($this->activeTemplate . 'partials.redirect_page', compact('url', 'img'));
    }


    public function redirectToProduct($productId)
    {
        $product = Product::findOrFail($productId);
        $click = $this->saveStoreClick($product, 1);
        $url = str_replace('{USERID}', $click, $product->url);
        $img = getImage(getFilePath('product') . '/' . $product->image);
        return view($this->activeTemplate . 'partials.redirect_page', compact('url', 'img'));
    }

    public function redirectToCoupon($couponId)
    {
        $coupon = Coupon::findOrFail($couponId);
        $click = $this->saveCouponClick($coupon, 1);
        $url = str_replace('{USERID}', $click, $coupon->url);
        $img = getImage(getFilePath('coupon') . '/' . $coupon->image);
        return view($this->activeTemplate . 'partials.redirect_page', compact('url', 'img'));
    }


    public function redirectToCategory($categoryId)
    {
        $category = StoresCategory::findOrFail($categoryId);
        $click = $this->saveCategoryClick($category, 1);
        $url = str_replace('{USERID}', $click, $category->url);
        $img = getImage(getFilePath('store') . '/' . $category->store->image);
        return view($this->activeTemplate . 'partials.redirect_page', compact('url', 'img'));
    }
}
