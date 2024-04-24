@extends($activeTemplate . 'layouts.frontend')

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





@section('content')

    <section class="inner-hero bg_img sections"
        style="background-image: url({{ getImage(getFilePath('category') . '/' . $category->image, '1920x720') }});

    padding: 25px 0;">

        <div class="container">

            <div class="row">

                <div class='store-info'>

                    <div class="store-item text-center has--link flex-shrink-0 store-item-store-page">

                        <span class='fav-counter'>

                            <i class="fa-heart  fas"></i>

                            <span>{{ $store->favorite->count() }}</span>

                        </span>

                        <div class="store-item__thumb" style='    display: contents;'>

                            <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}" alt="image"
                                stule='max-height: 30px;'>

                        </div>

                    </div>

                    <div class='store-info-text'>

                        <h2 class="title text-white">{{ __($store->name) }}</h2>

                        <h4 class="text-white">@lang('Cash Back Up To') {{ $store->cashback }}{{ $store->cashbacktype->sign }}</h4>

                        <p class="text-white">{{ __($store->description) }}</p>

                    </div>

                    <div class='store-btns'>

                        <div class='go-to-store'>

                            <a class='go-to-store-btn' target="_blank" 
                                href='{{ route('redirect.store', $store->id) }}'>{{ __('Go To Store') }}</a>

                            @if ($USERID != 0)
                                <a data-store-id='{{ $store->id }}' class='fav-icon' href="javascript:void(0)">



                                    @if ($isfav == 1)
                                        <i class="fa-heart text-danger fas"></i>
                                    @else
                                        <i class="far fa-heart"></i>
                                    @endif

                                </a>
                            @endif

                        </div>

                        <a class='store-terms' data-terms='{{ $store->terms }}' data-channels='{{ $store->channels }}'
                            data-store_image='{{ getImage(getFilePath('store') . '/' . $store->image) }}'
                            href="javascript:void(0)">{{ __('Terms & Conditions') }}</a>

                    </div>

                </div>



            </div>

        </div>

    </section>


    <section class="pt-80 pb-80 section--bg sections">

        <div class="container">

            <div class="section-header d-flex justify-content-between align-items-center gap-3">

                <h2 class="section-title" style="direction: ltr;">@lang('Cashback by Category')</h2>



            </div>

            <div class="col-xl-9 col-lg-8 ps-lg-4">

                <div class="row gy-4">

                    <div id="overlay">

                        <div class="cv-spinner">

                            <span class="spinner"></span>

                        </div>

                    </div>

                    <div class="overlay-2" id="overlay2"></div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Category')</th>
                                        <th>@lang('Cashback')</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($store->categories as $category)
                                        <tr>
                                            <td data-label="@lang('Category')">{{ __($category->name) }}</td>
                                            <td data-label="@lang('Cashback')">
                                                {{ $category->cashback }}{{ $store->cashbacktype->sign }}</td>
                                            <td>
                                                <a href="{{ route('redirect.category', $category->id) }}" target="_blank" 
                                                    class="btn-sm btn--base">
                                                    @lang('Get Offer')
                                                </a>
                                            </td>
                                        </tr>
                                    @empty

                                        <tr>

                                            <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>

                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>



                </div>



                <div id="ad-append" class="text-center"></div>

            </div>

        </div>



        </div>

    </section>

    <section class="pt-80 pb-80 section--bg sections">

        <div class="container">

            <div class="section-header d-flex justify-content-between align-items-center gap-3">

                <h2 class="section-title" style="direction: ltr;">{{ __($store->name) }} <span>{{ __('Offers') }}</span>
                </h2>



            </div>

            <div class="col-xl-9 col-lg-8 ps-lg-4">

                <div class="row gy-4">

                    <div id="overlay">

                        <div class="cv-spinner">

                            <span class="spinner"></span>

                        </div>

                    </div>

                    <div class="overlay-2" id="overlay2"></div>

                    @forelse($coupons as $coupon)
                        <div class="col-xl-4 col-sm-6">

                            <div class="coupon-item has--link">

                                <a href="javascript:void(0)" data-coupon="{{ $coupon }}"
                                    data-ending_date="{{ showDateTime($coupon->ending_date) }}"
                                    data-store_image="{{ getImage(getFilePath('store') . '/' . $coupon->store->image) }}"
                                    class="item--link coupon-details"></a>

                                <div class="coupon-item__thumb">

                                    <img src="{{ getImage(getFilePath('coupon') . '/' . $coupon->image, getFileSize('coupon')) }}"
                                        alt="image">

                                    @if ($coupon->featured_validity >= now())
                                        <span class="coupon-label">@lang('Featured')</span>
                                    @endif

                                </div>

                                <div class="coupon-item__content">

                                    <div class="coupon-item__meta">

                                        <a href="{{ route('coupon.filter.type', ['store', $coupon->store_id]) }}"
                                            class="store-name text--base">{{ __($coupon->store->name) }}</a>

                                    </div>

                                    <h4 class="title">{{ __($coupon->title) }}</h4>

                                    <div class="coupon-item__bottom">

                                        <span class="fs--14px">{{ $coupon->reports_count }} @lang('used today')</span>

                                    </div>

                                </div>

                            </div><!-- coupon-item end -->

                        </div>

                    @empty

                        <div class="text-center fw-bold">{{ __($emptyMessage) }}</div>
                    @endforelse



                </div>



                <div id="ad-append" class="text-center"></div>

            </div>

        </div>



        </div>

    </section>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

    <section class="pt-80 pb-80 section--bg sections">

        <div class="container">

            <div class="section-header d-flex justify-content-between align-items-center gap-3">

                <h2 class="section-title">{{ __('Similar Stores') }}</h2>

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
                                            <h3 class="me-2">{{ $store->cashback }}{{ $store->cashbacktype->sign }}
                                            </h3>

                                            <span>{{ $store->cashbacktype->id != 3 ? __('Cash Back Up To') : __('HalaMiles Up To') }}</span>
                                        @else
                                            <h4 class="me-2">{{ __('View store offers') }}</h4>
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



@endsection







@push('script')
    <script>
        $(document).ready(function() {

            // Set the CSRF token for all AJAX requests

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });



            $('.fav-icon').click(function() {

                var storeId = $(this).data('store-id');;

                var heartIcon = $(this).find('i'); // Get the heart icon element

                if (heartIcon.hasClass('text-danger')) {

                    // Remove the 'text-danger' class and switch back to the normal state

                    heartIcon.removeClass('text-danger').addClass('far').removeClass('fas');

                    heartIcon;

                } else {

                    // Add the 'text-danger' class to make the heart icon red

                    heartIcon.addClass('text-danger').addClass('fas').removeClass('far');



                }



                $.ajax({

                    url: '{{ route('saveFavorite') }}',

                    method: 'POST',

                    data: {

                        store_id: storeId

                    },



                });

            });

        });
    </script>
@endpush
