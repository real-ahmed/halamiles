@extends($activeTemplate.'layouts.app')
@section('panel')

@include($activeTemplate.'partials.preloader')
@include($activeTemplate.'partials.go_to_top')
@include($activeTemplate.'partials.frontend_header')

<main class="main-wrapper">
    @if (!request()->routeIs('home') && !request()->routeIs('store'))
        @include($activeTemplate.'partials.breadcrumb')
    @endif
    @yield('content')
</main>

@include($activeTemplate.'partials.coupon_modal')
@include($activeTemplate.'partials.store_terms')

@php
    $cookie = App\Models\Frontend::where('data_keys','cookie.data')->first();
@endphp
@if(($cookie->data_values->status == 1) && !\Cookie::get('gdpr_cookie'))
    <div class="cookies-card text-center hide">
      <div class="cookies-card__icon bg--base">
        <i class="las la-cookie-bite"></i>
      </div>
      <p class="mt-4 cookies-card__content">{{ __($cookie->data_values->short_desc) }} <a href="{{ route('cookie.policy') }}" target="_blank">@lang('learn more')</a></p>
      <div class="cookies-card__btn mt-4">
        <a href="javascript:void(0)" class="btn btn--base w-100 policy">@lang('Allow')</a>
      </div>
    </div>
@endif



@include($activeTemplate.'partials.footer')

@endsection