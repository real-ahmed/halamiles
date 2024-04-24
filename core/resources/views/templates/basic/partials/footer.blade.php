@php
    $policyPages = getContent('policy_pages.element', false, null, true);
    $footerPages = getContent('footer_pages.element', false, null, true);
    $socials = getContent('social_icon.element', false, null, true);
    
    $locale = app()->getLocale();
    
    // Filtering logic moved here
    $filteredPolicyPages = collect($policyPages)->filter(function ($policy) use ($locale) {
        return isset($policy->data_values->language) && $policy->data_values->language == $locale;
    });
    
    $filteredFooterPages = collect($footerPages)->filter(function ($page) use ($locale) {
        return isset($page->data_values->language) && $page->data_values->language == $locale;
    });
@endphp

<footer class="footer-section">
    <div class="footer-section__top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <a href="{{ route('home') }}" class="footer-logo">
                        <img src="{{ getImage(getFilePath('logoIcon') . '/logo_2.png') }}" alt="image">
                    </a>

                    <ul class="footer-inline-link justify-content-center mt-4">
                        @foreach ($filteredPolicyPages as $policy)
                            <li>
                                <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">
                                    {{ __($policy->data_values->title) }}
                                </a>
                            </li>
                        @endforeach

                        @foreach ($filteredFooterPages as $page)
                            <li>
                                <a href="{{ route('policy.pages', [slug($page->data_values->title), $page->id]) }}">
                                    {{ __($page->data_values->title) }}
                                </a>
                            </li>
                        @endforeach

                        <li><a href="{{ route('contact') }}">@lang('Contact Us')</a></li>
                    </ul>

                    <ul class="footer-inline-link justify-content-center mt-3">
                        @foreach ($socials as $social)
                            <li>
                                <a href="{{ $social->data_values->url }}" target="_blank">
                                    {!! $social->data_values->social_icon !!}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <p class="text-white">{{ date('h:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-section__bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <p class="text-white">@lang('Copyright') &copy; {{ date('Y') }}
                        <a href="{{ route('home') }}">{{ __($general->sitename) }}</a>.
                        @lang('All Right Reserved')
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
