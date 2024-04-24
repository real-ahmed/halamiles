@extends($activeTemplate . 'layouts.frontend')

@section('content')
@php
    $login = getContent('login.content', true);
@endphp
    <div class="section sections">
        <div class="container">
            <div class="row justify-content-between gy-5 align-items-center">
                <div class="col-lg-5">
                    <h3 class="title mb-4 pb-2">{{ __($login->data_values->heading) }}</h3>
                    <form method="POST" action="{{ route('user.login') }}" class="row verify-gcaptcha">
                        @csrf
                        <div class="col-12 mb-4">
                            <label for="email" class="form-label">@lang('Username or Email')</label>
                            <input type="text" name="username" value="{{ old('username') }}" class="form-control form--control" required>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="d-flex flex-wrap justify-content-between mb-2">
                                <label for="password" class="form-label mb-0">@lang('Password')</label>

                            </div>
                            <input id="password" type="password" class="form-control form--control" name="password" required>
                        </div>

                        <x-captcha></x-captcha>

                        <div class="col-md-6 mb-3">
                            <div class="form-group custom--checkbox">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    @lang('Remember Me')
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3 text-md-end text-start">
                            <a class="forgot-pass" href="{{ route('user.password.request') }}">
                                @lang('Forgot your password?')
                            </a>
                        </div>

                        <div class="col-12 mb-4">
                            <button type="submit" id="recaptcha" class="btn btn--base w-100">
                                @lang('Login')
                            </button>
                        </div>
                        <div class="col-12">
                            <p class="mb-0">@lang('Don\'t have any account?') <a href="{{ route('user.register') }}" class="forgot-pass">@lang('Register')</a></p>
                        </div>
                    </form>
                </div>
                <div class="col-lg-6">
                    <div class="section-thumb">
                        <img src="{{ getImage('assets/images/frontend/login/'.$login->data_values->image, '640x500') }}" alt="images" class="mw-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('style')
    <style>
        .forgot-pass {
            color: #05595b;
        }

        .forgot-pass:hover {
            color: #05595b;
        }

    </style>
@endpush
