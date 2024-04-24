@php
    
    $popularStore = getContent('popular_store.content', true);
    
    // Filter stores by the user's country code

$user = auth()->user();
$user_id = isset($user['id']) ? $user['id'] : 0;

if ($user_id != 0) {
    $USERCOUNTRY = $user->country_code;

    $stores = \App\Models\Store::where('featured', 1)

        ->where('status', 1)

        ->with([
            'coupons' => function ($coupon) {
                $coupon->where('status', 1);
            },
        ])

        ->latest()

        ->limit(10)

        ->get()

        ->filter(function ($store) use ($USERCOUNTRY) {
            $countries = $store->countries->pluck('country_code')->toArray();

            return in_array($USERCOUNTRY, $countries) || in_array('W', $countries);
        });
} else {
    $stores = \App\Models\Store::where('featured', 1)
        ->where('status', 1)

        ->with([
            'coupons' => function ($coupon) {
                $coupon->where('status', 1);
                },
            ])
            ->latest()
            ->limit(10)
            ->get();
    }
    
@endphp



<section class="pt-80 pb-80 section--bg sections">

    <div class="container">

        <div class="section-header d-flex justify-content-between align-items-center gap-3">

            <h2 class="section-title">{{ __($popularStore->data_values->heading) }}</h2>

            <a href="{{ route('popular.stores') }}" class="btn btn--base btn-md flex-shrink-0">@lang('View All')</a>

        </div>

        <div class="store-slider">

            @foreach ($stores as $store)
                <div class="single-slide">



                    <div class="store-item text-center has--link">



                        <a href="{{ route('store', ['id' => $store->id]) }}">

                            <div class="store-item__thumb">

                                <span class='fav-counter'>

                                    <i class="fa-heart  fas"></i>

                                    <span>{{ $store->favorite->count() }}</span>

                                </span>

                                <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}" alt="image">

                            </div>

                            <div class="store-item__content">

                                <div class="d-flex flex-wrap align-items-center justify-content-center text--base">
                                @if ($store->cashback != 0)
                                        @if ($store->hasoffer)
                                        <span style="font-size: 0.7rem;text-decoration: line-through;">@lang('Up To')
                                            {{ $store->getRawOriginal('cashback') }}{{ $store->cashbacktype->sign }}
                                            {{ $store->cashbacktype->id != 3 ? __('CashBack') : __('HalaMiles') }}</span>
                                        @endif
                                        <span>@lang('Up To')
                                            {{ $store->cashback }}{{ $store->cashbacktype->sign }}
                                            {{ $store->cashbacktype->id != 3 ? __('CashBack') : __('HalaMiles') }}</span>
                                        @else
                                        <span class="me-2">@lang('View store offers')</span>
                                        @endif

                                </div>

                            </div>

                        </a>

                    </div><!-- store-item end -->



                </div>
            @endforeach

        </div>

    </div>

</section>
