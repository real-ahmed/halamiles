<div class="header__top">


    <div class="container">
        <div class="row gy-2 align-items-center">
            <div class="col-lg-5 d-sm-block d-none">

            </div>
            <div class="col-lg-7">
                <div class="header-top-right justify-content-lg-end justify-content-center">
                    <!-- <ul class="header-social-links d-sm-flex d-none">
                        @foreach ($socials as $social)
<li><a href="{{ $social->data_values->url }}" target="_blank">@php echo $social->data_values->social_icon @endphp</a></li>
@endforeach
                    </ul> -->
                    <div class="header-top-action-wrapper">
                        <div class="language-select">
                            <i class="las la-globe"></i>
                            <select class="langSel">
                                @foreach ($language as $item)
                                    <option value="{{ $item->code }}"
                                        @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        @auth
                            <a href="{{ route('user.home') }}" class="header-user-btn me-3 text-white"><i
                                    class="las la-tachometer-alt"></i></i>@lang('Dashboard')</a>
                            <a href="{{ route('user.logout') }}" class="header-user-btn me-3 text-white"><i
                                    class="las la-user"></i>@lang('Logout')</a>
                        @else
                            <a href="{{ route('user.login') }}" class="header-user-btn me-3 text-white"><i
                                    class="las la-user"></i>@lang('Login')</a>
                            <a href="{{ route('user.register') }}" class="header-user-btn text-white">@lang('Register')</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
