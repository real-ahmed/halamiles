<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashbacktype;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\ModelWithdrawMethod;
use App\Models\Network;
use App\Models\Note;
use App\Models\Package;
use App\Models\Product;
use App\Models\Store;
use App\Models\StoresCategory;
use App\Models\User;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class ManageCouponController extends Controller
{

    protected $pageTitle = 'All Coupons';
    protected $view = 'admin.coupon.index';
    protected $coupons = null;

    protected $storeView = 'admin.coupon.stores';
    protected $storeFormView = 'admin.coupon.store_form';
    protected $stores = null;


    protected $productsView = 'admin.coupon.products';
    protected $products = null;

    public function allCoupons($store = null)
    {
        $data = $this->filterCoupons(null, $store);
        return view($this->view, $data);
    }

    protected function filterCoupons($scope = null, $store = null)
    {
        $coupons = Coupon::query();
        if ($scope) {
            $coupons = Coupon::$scope();
            $this->pageTitle = ucfirst($scope) . ' Coupons';
        }

        $searchKey = request()->search;

        if ($searchKey) {
            $coupons = $coupons->where(function ($query) use ($searchKey) {
                $query->where('title', 'like', "%$searchKey%")
                    ->orWhereHas('category', function ($category) use ($searchKey) {
                        $category->where('name', 'like', "%$searchKey%");
                    })->orWhereHas('store', function ($store) use ($searchKey) {
                        $store->where('name', 'like', "%$searchKey%");
                    })->orWhereHas('user', function ($user) use ($searchKey) {
                        $user->where('username', 'like', "%$searchKey%");
                    });
            });
        }

        if ($store) {
            $coupons = $coupons->where('store_id', $store);
        }

        $coupons = $coupons->with('user', 'category', 'store')->latest()->paginate(getPaginate());

        $data['coupons'] = $coupons;
        $data['pageTitle'] = $this->pageTitle;

        return $data;
    }

    public function pendingCoupons()
    {
        $data = $this->filterCoupons('pending');
        return view($this->view, $data);
    }

    public function activeCoupons()
    {
        $data = $this->filterCoupons('active');
        return view($this->view, $data);
    }

    public function expiredCoupons()
    {
        $data = $this->filterCoupons('expired');
        return view($this->view, $data);
    }

    public function rejectedCoupons()
    {
        $data = $this->filterCoupons('rejected');
        return view($this->view, $data);
    }

    public function todayDeal()
    {
        $data = $this->filterCoupons('todayDeal');
        return view($this->view, $data);
    }

    public function topDeal()
    {
        $data = $this->filterCoupons('topDeal');
        return view($this->view, $data);
    }

    public function couponForm($id = 0)
    {
        $pageTitle = ($id ? 'Update' : 'Add') . ' Coupon';
        $categories = Category::where('status', 1)->get();
        $coupon = $id ? Coupon::findOrFail($id) : '';
        $stores = Store::where('status', 1)->orderBy('user_id')->get();
        $countries = Country::all();
        $channels = Channel::all();
        $cashbacktypes = Cashbacktype::all();
        $networks = Network::all();

        return view('admin.coupon.coupon_form', compact('pageTitle', 'countries', 'channels', 'cashbacktypes', 'networks', 'categories', 'coupon', 'stores'));
    }

    public function saveCoupon(Request $request, $id = 0)
    {
        $imgValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'store_id' => 'required|integer|exists:stores,id',
            'coupon_code' => 'max:40',
            'ending_date' => 'required|date',
            'cashback' => 'required|numeric|gt:0',
            'url' => 'max:255',
            "store_id" => 'required|string|max:40',
            'description' => 'required',
            'image' => ["$imgValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $coupon = new Coupon();
        $notification = 'added';

        if ($id) {
            $coupon = Coupon::findOrFail($id);
            $coupon->status = $request->status ? 1 : 2;
            $notification = 'updated';
        }

        if ($request->hasFile('image')) {
            try {
                $old = $coupon->image;
                $coupon->image = fileUploader($request->image, getFilePath('coupon'), getFileSize('coupon'), $old);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $coupon->title = $request->title;
        $coupon->category_id = $request->category_id;
        $coupon->store_id = $request->store_id;
        $coupon->coupon_code = $request->coupon_code;
        $coupon->ending_date = $request->ending_date;
        $coupon->cashback = $request->cashback;
        $coupon->url = $request->url;
        $coupon->description = $request->description;
        $coupon->today_deal = $request->today_deal ? 1 : 0;
        $coupon->top_deal = $request->top_deal ? 1 : 0;
        $coupon->cashback_type = $request->cashbacktype_id;
        $coupon->user_percentage = $request->user_percentage;
        $coupon->save();
        $coupon->countries()->sync($request->input('countries_id'), true);

        $coupon->channels()->sync($request->input('channels_id'), true);

        $coupon->save();
        $notify[] = ['success', "Coupon $notification successfully"];
        return back()->withNotify($notify);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'action' => 'required|in:1,3',
            'coupon_id' => 'required|integer|min:1'
        ]);

        $coupon = Coupon::findOrFail($request->coupon_id);
        $coupon->status = $request->action;
        $coupon->reason = $request->reason;
        $coupon->save();

        $notification = $request->action == 1 ? 'approved' : 'rejected';
        $notify[] = ['success', "Coupon $notification successfully"];
        return back()->withNotify($notify);
    }


    public function allProducts($store = null)
    {
        $data = $this->filterProducts(null, $store);

        return view($this->productsView, $data);
    }

    protected function filterProducts($scope = null, $store = null)
    {
        $products = Product::query();
        $this->pageTitle = 'All Products';
        if ($scope) {
            // Assume $scope is a local scope function name in the Product model
            $products = Product::$scope();
            $this->pageTitle = ucfirst($scope) . ' Products';
        }

        $searchKey = request()->search;

        if ($searchKey) {
            $products = $products->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', "%$searchKey%")
                    ->orWhere('description', 'like', "%$searchKey%")
                    ->orWhereHas('category', function ($categoryQuery) use ($searchKey) {
                        $categoryQuery->where('name', 'like', "%$searchKey%");
                    });
            });
        }

        if ($store) {
            $products = $products->where('store_id', $store);
        }

        $products = $products->with(['category'])->latest()->paginate(getPaginate()); // Replace 10 with your desired pagination count

        $data['products'] = $products;
        $data['pageTitle'] = $this->pageTitle;

        return $data;
    }

    public function activeProducts()
    {
        $data = $this->filterProducts('active');
        return view($this->productsView, $data);
    }

    public function expiredProducts()
    {
        $data = $this->filterProducts('expired');
        return view($this->productsView, $data);
    }

    public function trendProducts()
    {
        $data = $this->filterProducts('trend');
        return view($this->productsView, $data);
    }

    public function productForm($id = 0)
    {
        $pageTitle = ($id ? 'Update' : 'Add') . ' Product';

        // Assume you have corresponding models for these entities.
        $categories = Category::where('status', 1)->get();
        $product = $id ? Product::findOrFail($id) : "";
        $stores = Store::where('status', 1)->orderBy('user_id')->get();
        $countries = Country::all();
        $channels = Channel::all();
        $cashbacktypes = CashbackType::all();
        $networks = Network::all();

        return view('admin.coupon.product_form', compact(
            'pageTitle',
            'categories',
            'product',
            'stores',
            'countries',
            'channels',
            'cashbacktypes',
            'networks'
        ));
    }

    public function saveProduct(Request $request, $id = 0)
    {
        $imgValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'store_id' => 'required|integer|exists:stores,id',
            'cashback' => 'required|numeric|gt:0',
            'cashbacktype_id' => 'required|integer',
            'ending_date' => 'required|date',
            'url' => 'nullable|url|max:255',
            'image' => ["$imgValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'countries_id' => 'required|array',
            'channels_id' => 'required|array',
            'description' => 'required|string',
        ]);

        $product = $id ? Product::findOrFail($id) : new Product();
        $notification = $id ? 'updated' : 'added';

        if ($request->hasFile('image')) {
            try {
                $old = $product->image;
                $product->image = fileUploader($request->image, getFilePath('product'), getFileSize('product'), $old);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $product->title = $request->title;
        $product->category_id = $request->category_id;
        $product->store_id = $request->store_id;
        $product->cashback = $request->cashback;
        $product->cashback_type = $request->cashbacktype_id;
        $product->ending_date = $request->ending_date;
        $product->url = $request->url;
        $product->description = $request->description;
        $product->status = $request->has('status') ? 1 : 0; // adjust according to your logic for status
        $product->trend = $request->has('trend') ? 1 : 0;
        $product->user_percentage = $request->user_percentage;

        $product->save();
        $product->countries()->sync($request->input('countries_id'));
        $product->channels()->sync($request->input('channels_id'));

        $notify[] = ['success', "Product $notification successfully"];
        return back()->withNotify($notify);
    }


    public function packages()
    {
        $pageTitle = 'All Package';
        $packages = Package::latest()->paginate(getPaginate());
        $categories = Category::paginate(getPaginate());

        return view('admin.coupon.packages', compact('pageTitle', 'packages', 'categories'));
    }

    public function savePackage(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|max:40',
            'duration' => 'required|integer|min:1',
            'price' => 'required|numeric|gt:0',
        ]);

        $package = new Package();
        $notification = 'added';

        if ($id) {
            $package = Package::findOrFail($id);
            $package->status = $request->status ? 1 : 0;
            $notification = 'updated';
        }

        $package->name = $request->name;
        $package->duration = $request->duration;
        $package->price = $request->price;
        $package->save();

        $notify[] = ['success', "Package $notification successfully"];
        return back()->withNotify($notify);
    }


    public function categories()
    {
        $pageTitle = 'All Stores Categories';
        $categories = StoresCategory::query();
        $searchKey = request()->search;
        if ($searchKey) {
            $categories = $categories->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', "%$searchKey%")
                    ->orWhereHas('store', function ($store) use ($searchKey) {
                        $store->where('name', 'like', "%$searchKey%");
                    });
            });
        }

        $categories = $categories->latest()->paginate(getPaginate());

        $stores = Store::where('status', 1)->orderBy('name')->get();
        $cashbacktypes = Cashbacktype::all();
        return view('admin.coupon.categories', compact('pageTitle', 'categories', 'stores', 'cashbacktypes'));
    }

    public function saveCategory(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|max:40',
            'cashback' => 'required|numeric|gt:0',
            'url' => 'required|max:255',
        ]);

        $category = new StoresCategory();
        $notification = 'added';

        if ($id) {
            $category = StoresCategory::findOrFail($id);
            $category->status = $request->status ? 1 : 0;
            $notification = 'updated';
        }

        $category->name = $request->name;
        $category->cashback = $request->cashback;
        $category->store_id = $request->store_id;
        $category->cashbacktype_id = $request->cashbacktype_id;
        $category->url = $request->url;
        $category->user_percentage = $request->user_percentage;


        $category->save();

        $notify[] = ['success', "Category $notification successfully"];
        return back()->withNotify($notify);
    }


    public function stores()
    {
        $data = $this->filterStores();
        $data['pageTitle'] = 'All Stores';
        $categories = Category::where('status', 1)->get();
        $countries = Country::all();
        $channels = Channel::all();
        $cashbacktypes = Cashbacktype::all();
        $networks = Network::all();
        return view($this->storeView, $data, compact('categories', 'countries', 'channels', 'cashbacktypes', 'networks'));
    }

    protected function filterStores($scope = null, $parameters = [])
    {
        $stores = Store::query();

        if ($scope) {
            $stores = $stores->$scope(...$parameters);
        }


        $searchKey = request()->search;

        if ($searchKey) {
            $stores = $stores->where(function ($query) use ($searchKey) {
                $query->where('name', 'like', "%$searchKey%")
                    ->orWhereHas('category', function ($category) use ($searchKey) {
                        $category->where('name', 'like', "%$searchKey%");
                    });
            });
        }

        $sortBy = request()->input('sort_by', 'created_at');
        $sortDirection = request()->input('sort_direction', 'desc');

        $stores = $stores->with('category', 'coupons', 'countries')
            ->orderBy($sortBy, $sortDirection)
            ->latest()
            ->paginate(getPaginate());


        $data['stores'] = $stores;

        return $data;
    }

    public function storeForm($id = 0)
    {

        $pageTitle = ($id ? 'Update' : 'Add') . ' Store';
        $store = $id ? Store::findOrFail($id) : '';
        $categories = Category::where('status', 1)->get();
        $countries = Country::all();
        $channels = Channel::all();
        $withdrawMethods = WithdrawMethod::where('status', 1)->get();
        $cashbacktypes = Cashbacktype::all();
        $networks = Network::all();
        return view($this->storeFormView, compact('categories', 'countries', 'channels', 'cashbacktypes', 'networks', 'pageTitle', 'store','withdrawMethods'));

    }

    public function active()
    {
        $data = $this->filterStores('active');
        $data['pageTitle'] = 'Active Stores';
        $categories = Category::where('status', 1)->get();
        $countries = Country::all();
        $channels = Channel::all();
        $cashbacktypes = Cashbacktype::all();
        $networks = Network::all();
        return view($this->storeView, $data, compact('categories', 'countries', 'channels', 'cashbacktypes', 'networks'));
    }

    public function featured()
    {
        $data = $this->filterStores('featured');
        $data['pageTitle'] = 'Featured Stores';
        $categories = Category::where('status', 1)->get();
        $countries = Country::all();
        $channels = Channel::all();
        $cashbacktypes = Cashbacktype::all();
        $networks = Network::all();
        return view($this->storeView, $data, compact('categories', 'countries', 'channels', 'cashbacktypes', 'networks'));
    }

    public function userFavorite($id)
    {
        $user = User::findOrFail($id);

        $favorites = $user->favorite()->pluck('store_id')->toArray();

        $data = $this->filterStores('whereIn', ['id', $favorites]);
        $data['pageTitle'] = $user->fullname . ' Favorites';
        $categories = Category::where('status', 1)->get();
        $countries = Country::all();
        $channels = Channel::all();
        $cashbacktypes = Cashbacktype::all();
        $networks = Network::all();

        return view($this->storeView, $data, compact('categories', 'countries', 'channels', 'cashbacktypes', 'networks'));
    }

    public function storeList(Request $request)
    {
        $query = Store::query();
        if (request()->search) {
            $query->where('name', 'like', "%$request->search%")->get();
        }

        $stores = $query->latest()->paginate(getPaginate());
        foreach ($stores as $store) {
            $response[] = [
                "id" => $store->id,
                "text" => $store->name,
            ];
        }

        return $response ?? [];
    }


    public function saveStore(Request $request, $id = 0)
    {


        $imgValidation = $id ? 'nullable' : 'required';
        $request->validate([
            'name' => 'required|max:40',
            'category_id' => 'required|int',
            'withdrawlmethod_id' => 'required|array',
            'countries_id' => 'required|array',
            'channels_id' => 'required|array',
            'withdrawlmethod_id.*' => 'exists:withdraw_methods,id', // Ensure each ID exists in the withdraw_methods table
            'image' => ["$imgValidation", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'cashback' => 'required|numeric',
            'url' => 'required|max:255',
        ]);

        $store = new Store();
        $notification = 'added';
        $oldImage = '';

        if ($id) {
            $store = Store::findOrFail($id);
            $store->status = $request->status ? 1 : 0;
            $notification = 'updated';
            $oldImage = $store->image;
        }

        if ($request->hasFile('image')) {
            try {
                $store->image = fileUploader($request->image, getFilePath('store'), null, $oldImage);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $store->name = $request->name;
        $store->category_id = $request->category_id;
        $store->cashbacktype_id = $request->cashbacktype_id;
        $store->network_id = $request->network_id;
        $store->cashback = $request->cashback;
        $store->user_percentage = $request->user_percentage;
        $store->offer_cashback = $request->offer_cashback;
        $store->ending_date = date('Y-m-d H:i:s', strtotime($request->ending_date)); // Format the date correctly

        $store->url = $request->url;
        $store->featured = $request->featured ? 1 : 0;
        $store->terms = $request->terms;
        $store->description = $request->description;



        // Get the valid withdrawal method IDs from the request
        $withdrawMethodIds = array_filter($request->withdrawlmethod_id, function($id) {
            return WithdrawMethod::find($id) !== null;
        });

        // Get current withdrawal methods attached to the store
        $currentMethods = $store->withdrawMethods->pluck('withdraw_method_id')->toArray();

        // Find IDs to add and remove
        $methodsToAdd = array_diff($withdrawMethodIds, $currentMethods);
        $methodsToRemove = array_diff($currentMethods, $withdrawMethodIds);

        // Remove the methods that are no longer associated
        foreach ($methodsToRemove as $methodId) {
            $store->withdrawMethods()->where('withdraw_method_id', $methodId)->delete();
        }

        // Add new methods
        foreach ($methodsToAdd as $methodId) {
            $withdrawMethod = WithdrawMethod::find($methodId);
            if ($withdrawMethod) {
                $modelWithdrawMethod = new ModelWithdrawMethod();
                $modelWithdrawMethod->withdrawMethod()->associate($withdrawMethod);
                $store->withdrawMethods()->save($modelWithdrawMethod);
            }
        }


        $store->save();
        $store->countries()->sync($request->input('countries_id'), true);

        $store->channels()->sync($request->input('channels_id'), true);
        $store->marketing_channels = json_encode(['social' => $request->social ? 1 : 0, 'email' => $request->email ? 1 : 0, 'cash' => $request->cash ? 1 : 0, 'coupon' => $request->coupon ? 1 : 0]);

        $store->save();

        if ($id) {
            $note = new Note();
            $note->note = $request->note;
            $store->notes()->save($note);
        }

        $notify[] = ['success', "Store $notification successfully"];
        return back()->withNotify($notify);
    }
}
