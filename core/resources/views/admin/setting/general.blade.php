@extends('admin.layouts.app')

@section('panel')

    <div class="row mb-none-30">

        <div class="col-lg-12 col-md-12 mb-30">

            <div class="card">

                <div class="card-body">

                    <form action="" method="POST">

                        @csrf

                        <div class="row">

                            <div class="col-md-4 col-sm-6">

                                <div class="form-group ">

                                    <label> @lang('Site Title')</label>

                                    <input class="form-control" type="text" name="site_name" required value="{{$general->site_name}}">

                                </div>

                            </div>

                            <div class="col-md-4 col-sm-6">

                                <div class="form-group ">

                                    <label>@lang('Currency')</label>

                                    <input class="form-control" type="text" name="cur_text" required value="{{$general->cur_text}}">

                                </div>

                            </div>

                            <div class="col-md-4 col-sm-6">

                                <div class="form-group ">

                                    <label>@lang('Currency Symbol')</label>

                                    <input class="form-control" type="text" name="cur_sym" required value="{{$general->cur_sym}}">

                                </div>

                            </div>

                            

                            <div class="form-group col-md-4 col-sm-6">

                                <label> @lang('Timezone')</label>

                                <select class="select2-basic" name="timezone">

                                    @foreach($timezones as $timezone)

                                    <option value="'{{ @$timezone}}'">{{ __($timezone) }}</option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label> @lang('Site Base Color')</label>

                                <div class="input-group">

                                    <span class="input-group-text p-0 border-0">

                                        <input type='text' class="form-control colorPicker" value="{{$general->base_color}}"/>

                                    </span>

                                    <input type="text" class="form-control colorCode" name="base_color" value="{{ $general->base_color }}"/>

                                </div>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label> @lang('Site Secondary Color')</label>

                                <div class="input-group">

                                    <span class="input-group-text p-0 border-0">

                                        <input type='text' class="form-control colorPicker" value="{{$general->secondary_color}}"/>

                                    </span>

                                    <input type="text" class="form-control colorCode" name="secondary_color" value="{{ $general->secondary_color }}"/>

                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Gift Credit')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="gift_credit" required value="{{$general->gift_credit}}">
                                            <span class="input-group-text">{{$general->cur_sym}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Referrer Credit')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="referrer_credit" required value="{{$general->referrer_credit}}">
                                            <span class="input-group-text">$</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Referral Credit')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="referral_credit" required value="{{$general->referral_credit}}">
                                            <span class="input-group-text">{{$general->cur_sym}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('minimum Referral')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="referral_min" required value="{{$general->referral_min}}">
                                            <span class="input-group-text">{{$general->cur_sym}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('Referral Days')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="referral_days" required value="{{$general->referral_days}}">
                                            <span class="input-group-text">D</span>
                                        </div>
                                    </div>
                                </div>



                                <div class="col-md-2 col-sm-6">
                                    <div class="form-group">
                                        <label>@lang('auto update (per hour)')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="transaction_update" required value="{{$general->transaction_update}}">
                                            <span class="input-group-text">H</span>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>


                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('Force Secure Password') <span class="text--primary" title="@lang('If you enable this, users have to set secure password.')"><i class="fas fa-info-circle"></i></span></label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="secure_password" @if($general->secure_password) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('Agree Policy') <span class="text--primary" title="@lang('If you enable this, users have to agree with all policies to be registered.')"><i class="fas fa-info-circle"></i></span></label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="agree" @if($general->agree) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('User Registration')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="registration" @if($general->registration) checked @endif>

                            </div>



                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('Force SSL')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disabled')" name="force_ssl" @if($general->force_ssl) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label> @lang('Email Verification')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="ev" @if($general->ev) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('Email Notification')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="en" @if($general->en) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label> @lang('Mobile Verification')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sv" @if($general->sv) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('SMS Notification')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="sn" @if($general->sn) checked @endif>

                            </div>

                            <div class="form-group col-md-4 col-sm-6">

                                <label>@lang('Coupon Auto Approve')</label>

                                <input type="checkbox" data-width="100%" data-size="large" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-height="50" data-on="@lang('Enable')" data-off="@lang('Disable')" name="coupon_auto_approve" @if($general->coupon_auto_approve) checked @endif>

                            </div>

                            <div class="col-12">

                        <div class="form-group">

                            <label>@lang('Stander Stores Terms')</label>

                            <textarea name="stander_terms" class="form-control" rows="4" required>{{$general->stander_terms}}</textarea>

                        </div>

                    </div>

                        </div>



                        <div class="form-group">

                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection



@push('script-lib')

    <script src="{{ asset('assets/admin/js/spectrum.js') }}"></script>

@endpush



@push('style-lib')

    <link rel="stylesheet" href="{{ asset('assets/admin/css/spectrum.css') }}">

@endpush



@push('script')

    <script>

        (function ($) {

            "use strict";

            $('.colorPicker').spectrum({

                color: $(this).data('color'),

                change: function (color) {

                    $(this).parent().siblings('.colorCode').val(color.toHexString().replace(/^#?/, ''));

                }

            });



            $('.colorCode').on('input', function () {

                var clr = $(this).val();

                $(this).parents('.input-group').find('.colorPicker').spectrum({

                    color: clr,

                });

            });



            $('.select2-basic').select2({

                dropdownParent: $('.card-body')

            });



            $('select[name=timezone]').val("'{{ config('app.timezone') }}'").select2();

            $('.select2-basic').select2({

                dropdownParent:$('.card-body')

            });

        })(jQuery);



    </script>

@endpush



