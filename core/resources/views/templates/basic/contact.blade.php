@extends($activeTemplate.'layouts.frontend')
@section('content')
@php
    $contact = getContent('contact_us.content', true);
    $socials = getContent('social_icon.element',false,null,true);
@endphp

<section class="pt-80 pb-80 sections">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-xxl-4 col-lg-5">
                <div class="contact-left-area">
                    <div class="contact-info-wrapper">
                        <div class="contact-info-list mb-4">
                            <div class="contact-info">
                                <div class="icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="content">
                                    <h6 class="title mb-1">@lang('Office Address')</h6>
                                    <p>{{ __($contact->data_values->contact_details) }}</p>
                                </div>
                            </div><!-- contact-info end -->
                            <div class="contact-info">
                                <div class="icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="content">
                                    <h6 class="title mb-1">@lang('Email Address')</h6>
                                    <p><a href="mailto:{{ $contact->data_values->email_address }}">{{ $contact->data_values->email_address }}</a></p>
                                </div>
                            </div><!-- contact-info end -->
                            <div class="contact-info">
                                <div class="icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="content">
                                    <h6 class="title mb-1">@lang('Phone Number')</h6>
                                    <p><a href="tel:{{ $contact->data_values->contact_number }}">{{ $contact->data_values->contact_number }}</a></p>
                                </div>
                            </div><!-- contact-info end -->
                        </div>
                        <h6 class="fs--16px text-center">@lang('Follow Us')</h6>
                        <ul class="social-list justify-content-center mt-3">
                            @foreach ($socials as $social)
                                <li><a href="{{ $social->data_values->url }}" target="_blank">@php echo $social->data_values->social_icon @endphp</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 mt-lg-0 mt-4">
                <div class="contact-right-area">
                    <div class="row mb-4">
                        <div class="col-lg-10">
                            <h3 class="title mb-2">{{ __($contact->data_values->title) }}</h3>
                            <p>{{ __($contact->data_values->short_details) }}</p>
                        </div>
                    </div>
                    <form method="POST" class="verify-gcaptcha">
                        @csrf
                        <div class="row gy-4">
                            <div class="col-lg-6">
                                <label>@lang('Full Name')</label>
                                <div class="custom-icon-field">
                                    <input type="text" name="name" class="form--control" value="{{ auth()->user() ? auth()->user()->fullname : old('name') }}" @if (auth()->user()) readonly @endif required>
                                    <i class="fas fa-user-alt"></i>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label>@lang('Email Address')</label>
                                <div class="custom-icon-field">
                                    <input type="email" name="email" class="form--control" value="@if(auth()->user()){{ auth()->user()->email }} @else {{ old('email') }}@endif" @if(auth()->user()) readonly @endif required>
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>@lang('Subject')</label>
                                <div class="custom-icon-field">
                                    <input type="text" name="subject" class="form--control" value="{{ old('subject') }}" required>
                                    <i class="fas fa-envelope"></i>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label>@lang('Message')</label>
                                <div class="custom-icon-field">
                                    <textarea name="message" class="form--control">{{ old('message') }}</textarea>
                                    <i class="fas fa-comment-alt"></i>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <x-captcha></x-captcha>
                            </div>
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="map-area">
    <iframe src = "{{ $contact->data_values->map_url }}"></iframe>
</div>

@if ($sections->secs != null)
    @foreach(json_decode($sections->secs) as $sec)
        @include($activeTemplate.'sections.'.$sec)
    @endforeach
@endif
@endsection