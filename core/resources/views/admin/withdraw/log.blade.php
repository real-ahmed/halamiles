@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
    @if(request()->routeIs('admin.withdraw.list'))
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--success has-link">
                <a href="{{ route('admin.withdraw.successful') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ $successful }}</h2>
                    <p class="text-white">@lang('Successful Withdraw')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--6 has-link">
                <a href="{{ route('admin.withdraw.pending') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ $pending }}</h2>
                    <p class="text-white">@lang('Pending Withdraw')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 has-link b-radius--5 bg--pink">
                <a href="{{ route('admin.withdraw.rejected') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ $rejected }}</h2>
                    <p class="text-white">@lang('Rejected Withdraw')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 has-link b-radius--5 bg--dark">
                <a href="{{ route('admin.withdraw.list') }}" class="item-link"></a>
                <div class="widget-two__content">
                    <h2 class="text-white">{{ $total }}</h2>
                    <p class="text-white">@lang('Total Withdraw')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
    @endif

    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Method | Transaction')</th>
                            <th>@lang('Initiated')</th>
                            <th>@lang('User')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($withdraws as $withdraw)
                            @php
                                $details = $withdraw->data ? json_encode($withdraw->data) : null;
                            @endphp
                            <tr>
                                <td data-label="@lang('Gateway | Transaction')">
                                     <span class="fw-bold"> {{ __(@$withdraw->method->name) }} </span>
                                     <br>
                                     <small> {{ $withdraw->transaction->id }} </small>
                                </td>

                                <td data-label="@lang('Date')">
                                    {{ showDateTime($withdraw->created_at) }}<br>{{ diffForHumans($withdraw->created_at) }}
                                </td>
                                <td data-label="@lang('User')">
                                    <span class="fw-bold">{{ $withdraw->transaction->user->fullname }}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ appendQuery('search',@$withdraw->transaction->user->username) }}"><span>@</span>{{ $withdraw->transaction->user->username }}</a>
                                    </span>
                                </td>
                                <td data-label="@lang('Amount')">
                                    {{ __($withdraw->method->symbol) }}{{ showAmount($withdraw->amount ) }} - <span class="text-danger" title="@lang('charge')">{{showAmount($withdraw->charge)}}</span>

                                    <br>
                                    <strong title="@lang('Amount with charge')">
                                    {{ __($withdraw->method->symbol) }}{{showAmount($withdraw->finalAmount)}}
                                    </strong>
                                </td>
                                <td data-label="@lang('Status')">
                                            @if($withdraw->transaction->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($withdraw->transaction->status == 1)
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.withdraw.details', $withdraw->id) }}"
                                       class="btn btn-sm btn-outline--primary ms-1">
                                        <i class="la la-desktop"></i> @lang('Details')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table><!-- table end -->
                </div>
            </div>
            @if ($withdraws->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($withdraws) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>


@endsection


@push('breadcrumb-plugins')
    @if(!request()->routeIs('admin.users.withdraws') && !request()->routeIs('admin.users.withdraws.method'))
        <form action="" method="GET">
            <div class="form-inline float-sm-end mb-2 ms-0 ms-xl-2 ms-lg-0">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Trx number/Username')" value="{{ request()->search ?? '' }}">
                    <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div class="form-inline float-sm-end">
                <div class="input-group">
                    <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control bg--white" data-position='bottom right' placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">
                    <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    @endif
@endpush


@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
  <script>
    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
  </script>
@endpush
