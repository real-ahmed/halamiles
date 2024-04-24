@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="section sections">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.clicks.claim.save') }}" class="row"
                                enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <ul class="list-group userData mb-2">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Coupon or Store')</span>
                                            <span>{{ __($click->model->title) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Date and time of purchase')</span>
                                            <span>{{ showDateTime($purchaseDate) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Click date')</span>
                                            <span>{{ showDateTime($click->created_at) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Type of Claim')</span>
                                            <span>{{ __($issue->type) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Order Value')</span>
                                            <span>{{ showAmount($orderAmount) }} {{ $general->cur_sym }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Expected Cashback')</span>
                                            <span>{{ showAmount($cashbackAmount) }} {{ $general->cur_sym }}</span>
                                        </li>

                                    </ul>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Order number')</label>
                                    <input type="text" name="order_number" value=""
                                        class="form-control form--control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Product name')</label>
                                    <input type="text" name="order_name" value=""
                                        class="form-control form--control" required>
                                </div>
                                <div class="form-group">
                                    <label for="voucher" class="form-label">@lang('Did you use any vouchers/codes used with your purchase ?')</label>
                                    <select name="voucher" class="form-select form--control" required>
                                        <option value="0">@lang('No')</option>
                                        <option value="1">@lang('Yes')</option>
                                    </select>
                                </div>
                                <div class="form-group voucher-form">
                                    <label for="email" class="form-label">@lang('vouchers/codes')</label>
                                    <input type="text" name="code" value="" class="form-control form--control">
                                </div>
                                <div class="form-group">
                                    <label for="link" class="form-label">@lang('Did you use another link ?')</label>
                                    <select name="link" class="form-select form--control" required>
                                        <option value="0">@lang('No')</option>
                                        <option value="1">@lang('Yes')</option>
                                    </select>
                                </div>
                                <div class="form-group url-form">
                                    <label for="email" class="form-label">@lang('Url')</label>
                                    <input type="text" name="another_link" value=""
                                        class="form-control form--control">
                                </div>
                                <div class="form-group">
                                    <label for="browser" class="form-label">@lang('Did you use any Private browser?')</label>
                                    <select name="browser" class="form-select form--control" required>
                                        <option value="0">@lang('No')</option>
                                        <option value="1">@lang('Yes')</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="vpn" class="form-label">@lang('Did you use any VPN ?')</label>
                                    <select name="vpn" class="form-select form--control" required>
                                        <option value="0">@lang('No')</option>
                                        <option value="1">@lang('Yes')</option>
                                    </select>
                                </div>
                                <div class="form-group custom--checkbox">

                                    <input type="checkbox" id="agree" name="agree" required>

                                    <label for="agree">@lang('I confirm that I have provided correct information and that this is a genuine and unique claim.') </label>
                                </div>


                                <div class="col-12">
                                    <input type="text" name='click_id' hidden value="{{ $click->id }}">
                                    <input type="text" name='issue_id' hidden value="{{ $issue->id }}">
                                    <input type="text" name='order_amount' hidden value="{{ $orderAmount }}">
                                    <input type="text" name='order_date' hidden value="{{ $purchaseDate }}">

                                    <button type="submit" class="btn btn--base w-100">
                                        @lang('Confirm')
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
        .profile-thumb,
        .profile-thumb .profilePicPreview {
            width: 280px;
            height: 190px;
        }

        @media (max-width:450px) {

            .profile-thumb,
            .profile-thumb .profilePicPreview {
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
        $(document).ready(function() {
            // Initially hide the voucher input
            $('.voucher-form').hide();
            $('.url-form').hide();

            // Listen for changes in the select dropdown
            $('select[name="voucher"]').change(function() {
                var selectedValue = $(this).val();

                if (selectedValue == "1") { // If "Yes" is selected
                    $('.voucher-form').slideDown(); // You can use show() if you don't want sliding effect
                } else {
                    $('.voucher-form').slideUp(); // You can use hide() if you don't want sliding effect
                }
            });
            $('select[name="link"]').change(function() {
                var selectedValue = $(this).val();

                if (selectedValue == "1") { // If "Yes" is selected
                    $('.url-form').slideDown(); // You can use show() if you don't want sliding effect
                } else {
                    $('.url-form').slideUp(); // You can use hide() if you don't want sliding effect
                }
            });
        });
    </script>
@endpush
