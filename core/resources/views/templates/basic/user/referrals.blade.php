@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="pt-30 pb-80 sections">

        <div class="container">
            <form class="coupon-copy-form mt-4" style="max-width: unset;" data-copy=true>
                    <input type="text" value="{{ route('user.register') }}?ref={{auth()->id()}}" id="coupon-text" readonly>
                    <button type="button" class="text-copy-btn copy-btn" data-bs-toggle="tooltip" data-bs-original-title="@lang('Copy to clipboard')">@lang('Copy')</button>
                    
            </form>
            <div class="mb-4 referral-terms" ><p>{{__('Get '). $referrer_credit.$cur_sym.__(" when you refer a friend, and your friend will get "). $referral_credit.$cur_sym. __(" if he spends ").$referral_min.$cur_sym.__(" before ").$referral_days. __(" days have passed.")}}</p></div>
            <div class="row justify-content-center g-4 mb-5">

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
            <div class="card custom--card border-0 dashboard-table-card">
                <div class="card-header section--bg d-flex flex-wrap justify-content-between algin-items-center">
                    <h4 class="title text--dark">@lang('My Referral list')</h4>
                    <a href="{{ route('user.referral.all') }}" class="btn btn--base btn-sm">@lang('View All')</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Transaction')</th>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Referral User')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($referrals as $referral)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Transaction')">{{ __($referral->referrerTransaction->id) }}</td>
                                        <td data-label="@lang('Date')">
                                            {{ showDateTime($referral->created_at) }}<br>{{ diffForHumans($referral->created_at) }}
                                        </td>
                                        <td data-label="@lang('Referral User')">{{ $referral->user->fullname }}</td>
                                        <td data-label="@lang('Amount')">{{$referral->userTransaction->amount}}</td>
                                    
                                        <td data-label="@lang('Status')">
                                            @if($referral->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($referral->status == 1)
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span class="badge badge--warning">@lang('Cancelled')</span>
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
                <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function($) {
        "use strict";
            $('.reasonBtn').on('click', function(){
                var modal   = $('#reasonModal');
                modal.find('.reason').text($(this).data('reason'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

