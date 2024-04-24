@php
$category = getContent('category.content', true);
$categories = \App\Models\Category::where('status', 1)
->latest()
->get();

$firstCategory = \App\Models\Category::where('status', 1)->first();
$firstCategoryId = $firstCategory ? $firstCategory->id : null;

$user = auth()->user();
$user_id = $user ? $user->id : 0;

$firstCategoryStores = collect();

if ($firstCategoryId) {
if ($user_id != 0 && $user->country_code) {
$USERCOUNTRY = $user->country_code;

$firstCategoryStores = \App\Models\Store::where('category_id', $firstCategoryId)
->where('status', 1)
->with(['coupons' => function ($query) {
$query->where('status', 1);
}])
->latest()
->limit(10)
->get()
->filter(function ($store) use ($USERCOUNTRY) {
$countries = $store->countries->pluck('country_code')->toArray();
return in_array($USERCOUNTRY, $countries) || in_array('W', $countries);
});
} else {
$firstCategoryStores = \App\Models\Store::where('category_id', $firstCategoryId)
->where('status', 1)
->take(15)
->get();
}
}
@endphp

<div class="category-section section--bg2 sections">
    <div class="container">
        <div class="row gy-4">
            {{-- <div class="col-lg-3">
                <div class="category-content justify-content-lg-start justify-content-center">
                    <h3 class="category-title">{{ __($category->data_values->heading) }}</h3>
        </div>
    </div> --}}
    <div class=" ps-lg-4">
        <div class="category-slider">
            @foreach ($categories as $category)
            <div class="single-slide">
                <div class="category-item has--link" data-category-id="{{ $category->id }}">
                    <a href="#" class="item--link"></a>
                    @php echo $category->icon @endphp
                    <p class="caption">{{ __($category->name) }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <!-- Add the store section here -->

    </div>
    <div class="store-section-container">
        <div class="store-section">
            @foreach ($firstCategoryStores as $store)
            <a class="store-card" href="{{ route('store', ['id' => $store->id]) }}">
                <div class="store-logo">
                    <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}" alt="{{ $store->name }}">
                </div>
                <div class="store-info">
                    <p class="store-name">{{ __($store->name) }}</p>
                </div>
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
                                        @endif
            </a>
            @endforeach

        </div>
        <div class="show-all-wrap">
            <a href="{{ route('stores') . '?category%5B%5D=' . $firstCategoryId }}" class="show-all-link">@lang('Show All')</a>
        </div>
    </div>
</div>
</div>
</div>
<div style="display: none" id="translationContainer" data-show-all="@lang('Show All')"></div>

@push('script')
<script>
    var categoryId;
    $(document).ready(function() {
        // Trigger click for the first category
        $('.category-item').first().find('.item--link').trigger('click');

        $('.item--link').on('click', function(event) {
            event.preventDefault(); // This prevents the default behavior of the anchor tag.

            categoryId = $(this).parent().data('category-id'); // Get the category ID

            // Load stores for the selected category
            loadStores(categoryId);
        });
    });

    function loadStores(categoryId) {
        $.ajax({
            url: '/get-stores/' + categoryId,
            method: 'GET',
            success: function(data) {
                updateStoreSection(data, categoryId);
            }
        });
    }

    function updateStoreSection(stores) {
        var storeHtml = '';
        var baseStoreURL = "{{ url('store') }}";

        stores.forEach(function(store) {
            var storeImage = "{{ getFilePath('store') }}" + "/" + store.image;
            var storeLink = baseStoreURL + '/' + store.id;
            storeHtml += '<a class="store-card"  href="' + storeLink + '">' +
                '<div class="store-logo">' +
                '<img src="' + storeImage + '" alt="' + store.name + '">' +
                '</div>' +
                '<div class="store-info">' +
                '<p class="store-name">' + store.name + '</p>' +
                '</div>' +
                '<span>' + store.cashback_info + '</span>' +
                '</a>';
        });

        // Adding the Show All link
        $('#all-link').attr('href', "{{ url('stores?category%5B%5D=') }}" + categoryId);


        $('.category-section .store-section').html(storeHtml);
    }
</script>
@endpush
@push('style')
<style>
    .store-section {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: flex-start;
    }

    .store-card {
        flex-basis: calc(20% - 20px);
        /* For larger screens, 5 cards per row */
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        background-color: #FFFFFF;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: black
    }

    .store-card:hover {
        color: inherit !important;
        text-decoration: inherit;

    }

    .store-logo img {
        max-height: 40px;
        margin-bottom: 5px;
    }

    .store-info .store-name {
        font-weight: bold;
        font-size: 16px;
        margin-bottom: 5px;
    }

    .store-info .store-cashback {
        font-size: 14px;
        color: #888;
    }

    .category-item {
        cursor: pointer;
        transition: opacity 0.3s;
    }

    .category-item:hover {
        opacity: 0.8;
    }

    /* For Tablets */
    @media (max-width: 768px) {
        .store-card {
            flex-basis: calc(50% - 20px);
            /* 2 cards per row */
        }
    }

    /* For Mobile */
    @media (max-width: 576px) {
        .store-card {
            flex-basis: 100%;
            /* 1 card per row */
        }

        .store-logo img {
            max-height: 30px;
            /* Slightly smaller images for mobile */
        }

        .store-info .store-name {
            font-size: 14px;
            /* Smaller text for mobile */
        }
    }

    .show-all-wrap {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .show-all-link {
        padding: 10px 20px;
        background-color: #222;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
    }

    .show-all-link:hover {
        background-color: #444;
        color: #fff !important;
    }



    /* For Mobile */
    @media (max-width: 982px) {
        .store-section {
            display: grid;
            grid-template-columns: repeat(5, calc(34.333% - 10px));
            /* 5 columns */
            grid-auto-rows: minmax(100px, auto);
            /* Row height */
            gap: 10px;
            overflow-x: auto;
            padding: 10px;
            -webkit-overflow-scrolling: touch;
            /* Smooth scrolling on iOS */
        }

        .store-card span {
            font-size: 0.6rem;
        }

        .store-card {
            width: 100%;
            /* Each card takes full width of the grid column */
            height: 100%;
            /* Card height is determined by grid-auto-rows */
        }

        .show-all-wrap {
            grid-column: span 5;
            /* Show All button spans across all columns */
            place-self: center;
        }

        .store-info .store-name {
            font-weight: bold;
            font-size: 0.8rem;
        }
    }
</style>
@endpush