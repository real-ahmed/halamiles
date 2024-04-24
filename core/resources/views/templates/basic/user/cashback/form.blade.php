@extends($activeTemplate.'layouts.master')

@section('content')
<div class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card custom--card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('user.coupon.save', $coupon ? $coupon->id : '') }}" class="row" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-6 mb-4">
                                <div class="profile-thumb-wrapper text-center">
                                    <div class="profile-thumb">
                                      <div class="avatar-preview">
                                        <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('coupon').'/'.@$coupon->image,getFileSize('coupon')) }})"></div>
                                      </div>
                                      <div class="avatar-edit" data-bs-toggle="tooltip" data-bs-placement="top" title="@lang('Upload Image')">
                                        <input type="file" class="profilePicUpload"  name="image"  accept=".jpg, .jpeg, .png" />
                                        <label for="image"><i class="las la-upload"></i> @lang('Update')</label>
                                      </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b> <br> @lang('Image will be resized into 300x200px') </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Title')</label>
                                    <input type="text" name="title" value="{{ $coupon ? $coupon->title : old('title') }}" class="form-control form--control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Coupon Code')</label>
                                    <input type="text" name="coupon_code" value="{{ $coupon ? $coupon->coupon_code : old('coupon_code') }}" class="form-control form--control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Ending Date')</label>
                                    <input type="text" name="ending_date" id="ending_date" value="{{ $coupon ? date_format($coupon->ending_date, 'd-m-Y h:i a') : old('ending_date') }}" class="form-control form--control" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                <label for="email" class="form-label">@lang('Category')</label>
                                <select name="category_id" class="form-select form--control" required>
                                    <option value="" hidden>@lang('Select One')</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $coupon ? ($coupon->category_id == $category->id ? 'selected' : '') : ''  }}>{{ __($category->name) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                <label for="store" class="form-label">@lang('Store')</label>
                                <select name="store_id" class="form-select form--control" required>
                                    <option value="" hidden>@lang('Select One')</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}" {{ $coupon ? ($coupon->store_id == $store->id ? 'selected' : '') : '' }}>{{ __($store->name) }}</option>
                                    @endforeach
                                </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">@lang('Cashback')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="cashback" value="{{ $coupon ? $coupon->cashback : old('cashback') }}" class="form-control form--control" required>
                                        <span class="input-group-text bg--base text-white">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="" class="form-label">@lang('Offer Url')</label>
                                    <input type="text" name="url" value="{{ $coupon ? $coupon->url : old('url') }}" class="form-control form--control" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Short Description')</label>
                                    <textarea name="description" rows="4" class="form-control" required>{{ $coupon ? $coupon->description : old('description') }}</textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn--base w-100">
                                    @lang('Submit')
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
<link href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
        .profile-thumb, .profile-thumb .profilePicPreview{
            width: 280px;
            height: 190px;
        }
        @media (max-width:450px) {
            .profile-thumb, .profile-thumb .profilePicPreview{
                width: 250px;
                height: 175px;
            }
        }
    </style>
@endpush

@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        var start = new Date(),
            prevDay,
            startHours = 0;

        start.setHours(0);
        start.setMinutes(0);

        if ([6, 0].indexOf(start.getDay()) != -1) {
            start.setHours(10);
            startHours = 10
        }
        $('#ending_date').datepicker({
            timepicker: true,
            language: 'en',
            dateFormat: 'dd-mm-yyyy',
            startDate: start,
            minHours: startHours,
            maxHours: 23,
            onSelect: function (fd, d, picker) {
                if (!d) return;

                var day = d.getDay();

                if (prevDay != undefined && prevDay == day) return;
                prevDay = day;

                if (day == 6 || day == 0) {
                    picker.update({
                        minHours: 0,
                        maxHours: 23
                    })
                } else {
                    picker.update({
                        minHours: 0,
                        maxHours: 23
                    })
                }
            }
        });

    })(jQuery);

</script>
@endpush
