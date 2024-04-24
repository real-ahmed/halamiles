@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="pt-80 pb-80 sections">
        <div class="container">
            <div class="row justify-content-center g-4 mb-5">
                <div class="col-sm-6 col-xl-4 ">
                    <a href="{{ route('user.cashbacks.pending') }}" class="dashboard__item ">
                        <span class="dashboard__icon">
                            <i class="las la-hand-holding-usd"></i>
                        </span>
                        <div class="cont">
                            <h3 class="title">{{ $general->cur_sym }}{{ showAmount($widget['pending_balance']) }}</h3>
                            <p class="text-dark">@lang('Pending Balance')</p>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <a href="{{ route('user.cashbacks.confirmed') }}" class="dashboard__item">
                        <span class="dashboard__icon">
                            <i class="las la-dollar-sign"></i>
                        </span>
                        <div class="cont">
                            <h3 class="title">{{ $general->cur_sym }}{{ showAmount($widget['confirmed_balance']) }}</h3>
                            <p class="text-dark">@lang('Confirmed Balance')</p>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <a href="{{ route('user.cashbacks.cancelled') }}" class="dashboard__item">
                        <span class="dashboard__icon">
                            <i class="las la-ban"></i>
                        </span>
                        <div class="cont">
                            <h3 class="title">{{ $general->cur_sym }}{{ showAmount($widget['cancelled_balance']) }}</h3>
                            <p class="text-dark">@lang('Cancelled Balance')</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row justify-content-center g-4 mb-5">
                <div class="col-sm-6 col-xl-4">
                    <a href="{{ route('user.cashbacks.points.pending') }}" class="dashboard__item">
                        <span class="dashboard__icon">
                            <i class="las la-pause-circle"></i>
                        </span>
                        <div class="cont">
                            <h3 class="title">{{ $widget['pending_points'] }}</h3>
                            <p class="text-dark">@lang('Pending Points')</p>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <a href="{{ route('user.cashbacks.points.confirmed') }}" class="dashboard__item">
                        <span class="dashboard__icon">
                            <i class="las la-check-circle"></i>
                        </span>
                        <div class="cont">
                            <h3 class="title">{{ $widget['confirmed_points'] }}</h3>
                            <p class="text-dark">@lang('Confirmed Points')</p>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <a href="{{ route('user.cashbacks.points.cancelled') }}" class="dashboard__item">
                        <span class="dashboard__icon">
                            <i class="las la-ban"></i>
                        </span>
                        <div class="cont">
                            <h3 class="title">{{ $widget['cancelled_points'] }}</h3>
                            <p class="text-dark">@lang('Cancelled Points')</p>
                        </div>
                    </a>
                </div>
            </div>
            <div class="card custom--card border-0 dashboard-table-card mb-5 shadow-lg">
                <div class="card-header section--bg d-flex flex-wrap justify-content-between align-items-center p-3">
                    <h4 class="title text--dark m-0">@lang('My Profile')</h4>
                    <a href="{{ route('user.profile.setting') }}" class="btn btn--base btn-sm">@lang('Edit My Profile')</a>
                </div>

                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="fw-bold">@lang('Full Name')</td>
                                    <td>{{ Auth::user()->fullname }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Email')</td>
                                    <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Phone')</td>
                                    <td>{{ Auth::user()->mobile }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">@lang('Member Since')</td>
                                    <td>{{ Auth::user()->created_at->format('d M Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <div class="card custom--card border-0 dashboard-table-card mb-5 shadow-lg">
                <div class="card-header section--bg d-flex flex-wrap justify-content-between algin-items-center">
                    <h4 class="title text--dark">@lang('My Cashback')</h4>
                    <a href="{{ route('user.cashbacks.all') }}" class="btn btn--base btn-sm">@lang('View All')</a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Title')">{{ __($transaction->title) }}</td>
                                        <td data-label="@lang('Category')">{{ __($transaction->category) }}</td>
                                        <td data-label="@lang('Amount')">
                                            {{ $general->cur_sym }}{{ $transaction->amount }}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($transaction->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($transaction->status == 1)
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>




            <div class="card custom--card border-0 dashboard-table-card shadow-lg mb-5 ">
                <div class="card-header section--bg d-flex flex-wrap justify-content-between algin-items-center">
                    <h4 class="title text--dark">@lang('My Clicks')</h4>
                    <a href="{{ route('user.clicks.history') }}" class="btn btn--base btn-sm">@lang('View All')</a>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Click id')</th>
                                    <th>@lang('Store/Coupon')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Type')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clicks as $click)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Click id')">{{ $click->id }}</td>
                                        <td data-label="@lang('Store/Coupon')">{{ __($click->model->title) }}</td>
                                        <td data-label="@lang('Date')">
                                            {{ showDateTime($click->created_at) }}<br>{{ diffForHumans($click->created_at) }}
                                        </td>
                                        <td data-label="@lang('Type')"> {{ __($click->type) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <div class="card custom--card border-0 dashboard-table-card shadow-lg">
                <div class="card-header section--bg d-flex flex-wrap justify-content-between align-items-center p-3">
                    <h4 class="title text--dark m-0">@lang('Referrals')</h4>
                    <a href="{{ route('user.referrals') }}" class="btn btn--base btn-sm">@lang('View All')</a>
                </div>

                <div class="card-body p-4">
                    <form class="coupon-copy-form  mb-4" style="max-width: unset;" data-copy=true>
                        <input type="text" value="{{ route('user.register') }}?ref={{ auth()->id() }}"
                            id="coupon-text" readonly>
                        <button type="button" class="text-copy-btn copy-btn" data-bs-toggle="tooltip"
                            data-bs-original-title="@lang('Copy to clipboard')">@lang('Copy')</button>

                    </form>
                    <div class="row justify-content-center g-4 ">

                        <div class="col-sm-6 col-xl-4">
                            <a href="{{ route('user.referral.confirmed') }}" class="dashboard__item">
                                <span class="dashboard__icon">
                                    <i class="las la-check-circle"></i>
                                </span>
                                <div class="cont">
                                    <h3 class="title">{{ $widget['confirmed_referral'] }}</h3>
                                    <p class="text-dark">@lang('Confirmed Referrals')</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-6 col-xl-4">
                            <a href="{{ route('user.referral.pending') }}" class="dashboard__item">
                                <span class="dashboard__icon">
                                    <i class="las la-pause-circle"></i>
                                </span>
                                <div class="cont">
                                    <h3 class="title">{{ $widget['pending_referral'] }}</h3>
                                    <p class="text-dark">@lang('Pending Referrals')</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-xl-4">
                            <a href="{{ route('user.referral.cancelled') }}" class="dashboard__item">
                                <span class="dashboard__icon">
                                    <i class="las la-ban"></i>
                                </span>
                                <div class="cont">
                                    <h3 class="title">{{ $widget['cancelled_referral'] }}</h3>
                                    <p class="text-dark">@lang('Cancelled Referrals')</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal btnSize="btn-sm" btnBase="btn--base"></x-confirmation-modal>
    <div id="reasonModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Rejected Reason')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="reason"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn-sm"
                        data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.reasonBtn').on('click', function() {
                var modal = $('#reasonModal');
                modal.find('.reason').text($(this).data('reason'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
