@php
$contact = getContent('contact_us.content', true);
$socials = getContent('social_icon.element', false, null, true);
$popularStore = getContent('popular_store.content', true);

$infoPages = getContent('info_pages.element', false, null, true);
$locale = app()->getLocale();

// Filtering logic for $infoPages
$filteredInfoPages = collect($infoPages)->filter(function ($page) use ($locale) {
return isset($page->data_values->language) && $page->data_values->language == $locale;
});
@endphp
<header class="header">
    @include($activeTemplate . 'partials.top_header')
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl align-items-center">
                <a class="site-logo site-title" href="{{ route('home') }}">
                    <img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="logo">
                </a>

                <div class="nav-right justify-content-xl-end mobile-search">
                    <form action="{{ route('stores') }}" class="header-search">
                        <input type="search" name="search_key" value="{{ request()->search_key }}" class="header-search__input" placeholder="@lang('Search')">
                        <i class="las la-search"></i>
                    </form>
                </div>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu m-auto">
                        <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                        <li><a href="{{ route('coupon.search') }}">@lang('Coupons')</a></li>
                        <li><a href="{{ route('stores') }}">@lang('Stores')</a></li>
                        <li><a href="{{ route('products') }}">@lang('Products')</a></li>

                        <li><a href="{{ route('popular.stores') }}">{{ __($popularStore->data_values->heading) }}</a>
                        </li>
                        @foreach ($filteredInfoPages as $page)
                        <li>
                            <a href="{{ route('policy.pages', [slug($page->data_values->title), $page->id]) }}">
                                {{ __($page->data_values->title) }}
                            </a>
                        </li>
                        @endforeach
                        @foreach ($pages as $k => $data)
                        <li><a href="{{ route('pages', $data->slug) }}">{{ __($data->name) }}</a></li>
                        @endforeach
                        <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>

                    </ul>
                    <div class="nav-right justify-content-xl-end desktop-search">
                        <form action="{{ route('stores') }}" class="header-search">
                            <input type="search" name="search_key" value="{{ request()->search_key }}" class="header-search__input" placeholder="@lang('Search')">
                            <i class="las la-search"></i>
                        </form>
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- header__bottom end -->
</header>