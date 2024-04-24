@extends($activeTemplate . 'layouts.frontend')



@section('content')
<section class="pt-80 pb-80 section--bg sections">

    <div class="container">
        <div class="row gy-3">
            <div class="col-xl-3 col-lg-4">

                <button type="button" class="sidebar-open-btn"><i class="las la-sliders-h"></i>@lang('Filter Menu')</button>

                <div class="sidebar">

                    <button type="button" class="sidebar-close-btn"><i class="las la-times"></i></button>

                    <form action="{{ route('stores') }}" class="search-form">

                        <div class="sidebar-widget">

                            <div class="input-group mb-3">

                                <input type="text" class="form-control form--control" name="search_key" value="{{ request()->search_key }}" placeholder="@lang('Search')">

                                <button class="input-group-text bg--base text-white border--base px-3"><i class="fas fa-search"></i></button>

                            </div>

                        </div><!-- sidebar-widget end -->
                        <div class="sidebar-widget">

                            <h6 class="sidebar-widget__title">@lang('Sort by')</h6>

                            <div class="checkbox-wrapper">

                                <div class="form-group">

                                    <select name="sort_by" class="form-control" required>

                                        <option value="created_at" @if (request()->input('sort_by') == 'created_at') selected @endif>
                                            @lang('Newest')</option>
                                        <option value="name" @if (request()->input('sort_by') == 'name') selected @endif>
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

                                    <input class="form-check-input category-check" type="checkbox" value="all" id="category-all">

                                    <label class="form-check-label" for="category-all">

                                        @lang('All')

                                    </label>

                                </div>

                                @foreach ($categories as $category)
                                <div class="form-check custom--checkbox">

                                    <input class="form-check-input category-check" type="checkbox" name="category[]" value="{{ $category->id }}" id="{{ 'category-' . $category->id }}" {{ request()->category && count(request()->category) ? (in_array($category->id, request()->category) ? 'checked' : '') : '' }}>

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
            <div class="col">
                <div id="overlay">

                    <div class="cv-spinner">

                        <span class="spinner"></span>

                    </div>

                </div>

                <div class="overlay-2" id="overlay2"></div>

                @if (request()->search_key)
                <p>{{ $stores->count() }} @lang('stores found for') "{{ request()->search_key }}"</p>
                @endif
                <div class="d-flex flex-wrap gap-4">



                    @forelse ($stores as $store)
                    <div class="store-item text-center  has--link flex-shrink-0">

                        <a href="{{ route('store', ['id' => $store->id]) }}">

                            <div class="store-item__thumb">

                                <span class='fav-counter'>

                                    <i class="fa-heart  fas"></i>

                                    <span>{{ $store->favorite->count() }}</span>

                                </span>

                                <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}" alt="image">

                            </div>

                            <div class="store-item__content">

                                <div class="d-flex flex-wrap align-items-center justify-content-center  text--base">


                                    <div class="cashback-container text--base">
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

                            </div>

                        </a>

                    </div>

                    @empty

                    <div class="text-center">

                        {{ __($emptyMessage) }}

                    </div>
                    @endforelse

                </div>
            </div>
        </div>
        {{ $stores->links() }}

    </div>

</section>
@endsection





@push('style')
<style>
    .store-item {

        width: calc((100% / 5) + 30px)
    }

    @media (max-width:1199px) {

        .store-item {

            width: calc((100% / 4) + 35px)
        }

    }

    @media (max-width: 767px) {

        .store-item {

            width: calc((100% / 3) - 16px)
        }

    }

    @media (max-width: 520px) {

        .store-item {

            width: calc((100% / 2) - 12px)
        }

    }

    @media (max-width: 400px) {

        .store-item {

            width: 100%
        }

    }
</style>
@endpush
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