@extends($activeTemplate . 'layouts.frontend')



@section('content')
    <section class="pt-80 pb-80 sections">

        <div class="container">

            <div class="row gy-3">

                <div class="col-xl-3 col-lg-4">

                    <button type="button" class="sidebar-open-btn"><i class="las la-sliders-h"></i>@lang('Filter Menu')</button>

                    <div class="sidebar">

                        <button type="button" class="sidebar-close-btn"><i class="las la-times"></i></button>

                        <form action="{{ route('coupon.search') }}" class="search-form">

                            <div class="sidebar-widget">

                                <div class="input-group mb-3">

                                    <input type="text" class="form-control form--control" name="search_key"
                                        value="{{ request()->search_key }}" placeholder="@lang('Search')">

                                    <button class="input-group-text bg--base text-white border--base px-3"><i
                                            class="fas fa-search"></i></button>

                                </div>

                            </div><!-- sidebar-widget end -->
                            <div class="sidebar-widget">

                                <h6 class="sidebar-widget__title">@lang('Sort by')</h6>

                                <div class="checkbox-wrapper">

                                    <div class="form-group">

                                        <select name="sort_by" class="form-control" required>

                                            <option value="created_at" @if (request()->input('sort_by') == 'created_at') selected @endif>
                                                @lang('Newest')</option>
                                            <option value="title" @if (request()->input('sort_by') == 'name') selected @endif>
                                                @lang('Name')</option>
                                            <option value="cashback" @if (request()->input('sort_by') == 'cashback') selected @endif>
                                                @lang('CashBack')</option>


                                        </select>



                                    </div>

                                    <div class="form-group">

                                        <select name="sort_direction" class="form-control" required>
                                            <option value="asc" @if (request()->input('sort_direction') == 'asc') selected @endif>
                                                @lang('Ascending')</option>
                                            <option value="desc" @if (request()->input('sort_direction') == 'desc') selected @endif>
                                                @lang('Descending')</option>



                                        </select>



                                    </div>

                                </div>

                            </div><!-- sidebar-widget end -->

                            <div class="sidebar-widget">

                                <h6 class="sidebar-widget__title">@lang('Categories')</h6>

                                <div class="checkbox-wrapper">

                                    <div class="form-check custom--checkbox">

                                        <input class="form-check-input category-check" type="checkbox" value="all"
                                            id="category-all">

                                        <label class="form-check-label" for="category-all">

                                            @lang('All')

                                        </label>

                                    </div>

                                    @foreach ($categories as $category)
                                        <div class="form-check custom--checkbox">

                                            <input class="form-check-input category-check" type="checkbox" name="category[]"
                                                value="{{ $category->id }}" id="{{ 'category-' . $category->id }}"
                                                {{ request()->category && count(request()->category) ? (in_array($category->id, request()->category) ? 'checked' : '') : '' }}>

                                            <label class="form-check-label" for="{{ 'category-' . $category->id }}">

                                                {{ __($category->name) }}

                                            </label>

                                        </div>
                                    @endforeach

                                    <button class="btn btn--base btn-sm mt-3 w-100">@lang('Filter')</button>

                                </div>

                            </div><!-- sidebar-widget end -->

                    </div><!-- sidebar end -->

                    </form>

                    <div class="promo-wrapper">

                        <div class="promo-item">

                            @php showAd('370x670') @endphp

                        </div>

                        <div class="promo-item">

                            @php showAd('300x250') @endphp

                        </div>

                        <div class="promo-item">

                            @php showAd('370x670') @endphp

                        </div>

                    </div>

                </div>

                <div class="col-xl-9 col-lg-8 ps-lg-4">

                    <div class="row gy-4">

                        <div id="overlay">

                            <div class="cv-spinner">

                                <span class="spinner"></span>

                            </div>

                        </div>

                        <div class="overlay-2" id="overlay2"></div>

                        @if (request()->search_key)
                            <p>{{ $coupons->count() }} @lang('coupons found for') "{{ request()->search_key }}"</p>
                        @endif

                        @forelse($coupons as $coupon)
                            <div class="col-xl-4 col-sm-6">

                                <div class="coupon-item has--link">

                                    <a href="javascript:void(0)" data-coupon="{{ $coupon }}"
                                        data-cashback="{{ $coupon->cashback . ' ' }}{{ $coupon->cashbacktype->sign . ' ' }}{{ $coupon->cashbacktype->id != 3 ? __('CashBack') : __('HalaMiles') }}"
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

                        {{ $coupons->links() }}



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
@endsection



@push('script')
    <script>
        (function($) {

            "use strict";



            $('.category-check').on('click', function(e) {

                var categoryArr = $('.category-check:checked:checked');

                if (e.target.value == 'all') {

                    $('input:checkbox').not(this).prop('checked', false);

                    return 0;

                } else {

                    $('#category-all').prop('checked', false);

                }

            });



        })(jQuery);
    </script>
@endpush
