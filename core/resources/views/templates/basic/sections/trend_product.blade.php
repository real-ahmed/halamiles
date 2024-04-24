@php
    $trendingProductContent = getContent('trending_product.content', true);
    $user = auth()->user();
    $user_id = isset($user['id']) ? $user['id'] : 0;

    if ($user_id != 0) {
        $USERCOUNTRY = $user->country_code;

        $products = \App\Models\Product::where('trend', 1)
            ->where('status', 1)
            ->latest()
            ->limit(10)
            ->get()
            ->filter(function ($product) use ($USERCOUNTRY) {
                $countries = $product->countries->pluck('country_code')->toArray();

                return in_array($USERCOUNTRY, $countries) || in_array('W', $countries);
            });
    } else {
        $products = \App\Models\Product::where('trend', 1)
            ->where('status', 1)
            ->latest()
            ->limit(10)
            ->get();
    }
@endphp

<section class="pt-80 pb-80 section--bg sections">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center gap-3">
            <h2 class="section-title">{{ __($trendingProductContent->data_values->heading) }}</h2>
            <a href="{{ route('products', 'trend') }}" class="btn btn--base btn-md flex-shrink-0">@lang('View All')</a>
        </div>

        <div class="store-slider">
            @foreach ($products as $product)
                <div class="single-slide" style="padding : 1.3125rem 0.625rem;">



                    <div class="coupon-item has--link">

                        <a href="{{ route('redirect.product', $product->id) }}" class="item--link coupon-details"></a>

                        <div class="coupon-item__thumb">

                            <img src="{{ getImage(getFilePath('product') . '/' . $product->image, getFileSize('product')) }}"
                                alt="image">



                        </div>

                        <div class="coupon-item__content">

                            <div class="coupon-item__meta">

                                <a href="{{ route('store', $product->store_id) }}"
                                    class="store-name text--base">{{ __($product->store->name) }}</a>

                            </div>

                            <h4 class="title">{{ __($product->title) }}</h4>
                            <span>
                                {{ $product->cashback }}{{ $product->cashbacktype->sign }}
                                {{ $product->cashbacktype->id != 3 ? __('CashBack') : __('HalaMiles') }}</span>
                            <p style="font-size: 0.7rem">{{ $product->description }}</p>
                        </div>

                    </div><!-- product-item end -->



                </div>
            @endforeach
        </div>
    </div>
</section>
