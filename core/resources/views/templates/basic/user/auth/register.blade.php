@extends($activeTemplate . 'layouts.frontend')

@section('content')

    @php
        
        $policyPages = getContent('policy_pages.element', false, null, true);
        
        $register = getContent('register.content', true);
        
    @endphp

    <div class="section sections">

        <div class="container">

            <div class="row justify-content-between gy-5 align-items-center">

                <div class="col-lg-6">

                    <h3 class="title mb-4 pb-2">{{ __($register->data_values->heading) }}</h3>

                    <form action="{{ route('user.register') }}" method="POST" class="verify-gcaptcha">

                        @csrf

                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-label">@lang('Username')</label>

                                    <input type="usename"
                                        class="form-control @error('username') is-invalid @enderror form--control checkUser"
                                        name="username" value="{{ old('username') }}" required>

                                    <small class="text-danger usernameExist"></small>

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-label">@lang('E-Mail Address')</label>

                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror form--control checkUser"
                                        name="email" value="{{ old('email') }}" required>

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-label">@lang('Country')</label>

                                    <select name="country"
                                        class="form-select @error('country') is-invalid @enderror form--control">

                                        @foreach ($countries as $key => $country)
                                            @php
                                                
                                                $selected = $country->country === $user_country_code ? 'selected' : '';
                                                
                                            @endphp

                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                value="{{ $country->country }}" data-code="{{ $key }}"
                                                {{ $selected }}>

                                                {{ __($country->country) }}

                                            </option>
                                        @endforeach

                                    </select>



                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-label">@lang('Mobile')</label>

                                    <div class="input-group ">

                                        <span class="input-group-text mobile-code bg--base text-white">

                                        </span>

                                        <input type="hidden" name="mobile_code">

                                        <input type="hidden" name="country_code">

                                        <input type="phone" name="mobile" maxlength="12" value="{{ old('mobile') }}"
                                            class="form-control @error('phone') is-invalid @enderror form--control checkUser"
                                            required>

                                    </div>

                                    <small class="text-danger mobileExist"></small>

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-label">@lang('Password')</label>

                                    <input type="password"
                                        class=" @error('password') is-invalid @enderror form-control form--control"
                                        name="password" required>

                                    @if ($general->secure_password)
                                        <div class="input-popup">x

                                            <p class="error lower">@lang('1 small letter minimum')</p>

                                            <p class="error capital">@lang('1 capital letter minimum')</p>

                                            <p class="error number">@lang('1 number minimum')</p>

                                            <p class="error special">@lang('1 special character minimum')</p>

                                            <p class="error minimum">@lang('6 character password')</p>

                                        </div>
                                    @endif

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-label">@lang('Confirm Password')</label>

                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror form--control"
                                        name="password_confirmation" required>

                                </div>

                            </div>

                            <x-captcha></x-captcha>

                        </div>

                        @if ($general->agree)
                        <div class="form-group custom--checkbox">
                            <input type="checkbox" id="agree" @checked(old('agree')) name="agree" required>
                            <label for="agree">@lang('I agree with')
                                @php
                                    $filteredPolicyPages = $policyPages->filter(function ($policy) {
                                        return isset($policy->data_values->language) && $policy->data_values->language == app()->getLocale();
                                    });
                                @endphp
            
                                @foreach ($filteredPolicyPages as $key => $policy)
                                    <a href="{{ route('policy.pages', [slug($policy->data_values->title), $policy->id]) }}">{{ __($policy->data_values->title) }}</a>
            
                                    @if ($key !== $filteredPolicyPages->keys()->last())
                                        ,
                                    @endif
                                @endforeach
            
                            </label>
                        </div>
            
                        <div class="form-group custom--checkbox">
                            <input type="checkbox" checked id="latest_news" name="latest_news" value="1">
                            <label for="latest_news">@lang('I agree to get the latest news') </label>
                        </div>
            
                        <div id="agree-error" class="text-danger" style="display:none;">@lang("Please check the 'I agree with Privacy Policy' and 'Terms of Service' checkboxes.")</div>
                    @endif

                        <div class="form-group">
                            <input type="hidden" name="ref" value="{{ request()->query('ref') }}">

                            <button type="submit" id="recaptcha" class="btn btn--base w-100"> @lang('Register')</button>

                        </div>

                        <p class="mb-0">@lang('Already have an account?') <a href="{{ route('user.login') }}">@lang('Login')</a></p>

                    </form>

                </div>

                <div class="col-lg-6">

                    <div class="section-thumb">

                        <img src="{{ getImage('assets/images/frontend/register/' . $register->data_values->image, '640x500') }}"
                            alt="images" class="mw-100">

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>



    <div class="modal fade" id="existModalCenter" tabindex="-1" role="dialog" aria-labelledby="existModalCenterTitle"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="existModalLongTitle">@lang('You are with us')</h5>

                    <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <i class="las la-times"></i>

                    </span>

                </div>

                <div class="modal-body">

                    <h6 class="text-center">@lang('You already have an account please Login')</h6>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-dark btn-sm"
                        data-bs-dismiss="modal">@lang('Close')</button>

                    <a href="{{ route('user.login') }}" class="btn btn--base btn-sm">@lang('Login')</a>

                </div>

            </div>

        </div>

    </div>

@endsection

@push('style')
    <style>
        .country-code .input-group-text {

            background: #fff !important;

        }



        .country-code select {

            border: none;

        }



        .country-code select:focus {

            border: none;

            outline: none;

        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/global/js/secure_password.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";

        (function($) {

            $('#recaptcha').on('click', function(e) {
        if (!$('#agree').is(':checked')) {
            e.preventDefault();
            $('#agree-error').show();
        }
    });

            @if ($mobile_code)

                $(`option[data-code={{ $mobile_code }}]`).attr('selected', '');
            @endif



            $('select[name=country]').change(function() {

                $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));

                $('input[name=country_code]').val($('select[name=country] :selected').data('code'));

                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            });

            $('input[name=mobile_code]').val($('select[name=country] :selected').data('mobile_code'));

            $('input[name=country_code]').val($('select[name=country] :selected').data('code'));

            $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));

            @if ($general->secure_password)

                $('input[name=password]').on('input', function() {

                    secure_password($(this));

                });



                $('[name=password]').focus(function() {

                    $(this).closest('.form-group').addClass('hover-input-popup');

                });



                $('[name=password]').focusout(function() {

                    $(this).closest('.form-group').removeClass('hover-input-popup');

                });
            @endif



            $('.checkUser').on('focusout', function(e) {

                var url = '{{ route('user.checkUser') }}';

                var value = $(this).val();

                var token = '{{ csrf_token() }}';

                if ($(this).attr('name') == 'mobile') {

                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;

                    var data = {

                        mobile: mobile,

                        _token: token

                    }

                }

                if ($(this).attr('name') == 'email') {

                    var data = {

                        email: value,

                        _token: token

                    }

                }

                if ($(this).attr('name') == 'username') {

                    var data = {

                        username: value,

                        _token: token

                    }

                }

                $.post(url, data, function(response) {

                    if (response.data != false && response.type == 'email') {

                        $('#existModalCenter').modal('show');

                    } else if (response.data != false) {

                        $(`.${response.type}Exist`).text(`${response.type} already exist`);

                    } else {

                        $(`.${response.type}Exist`).text('');

                    }

                });

            });

        })(jQuery);
    </script>
@endpush
