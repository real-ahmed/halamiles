@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">


    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Store/Coupon')</th>
                                    <th>@lang('Claim Type')</th>
                                    <th>@lang('Order Value')</th>
                                    <th>@lang('Expected Cashback')</th>
                                    <th>@lang('Purchase date')</th>
                                    <th>@lang('Click date')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($claims as $claim)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                                <td data-label="@lang('Store/Coupon')">{{ __($claim->click->model->name) }}</td>
                                <td data-label="@lang('Claim Type')">{{ __($claim->issue->type) }}</td>
                                <td data-label="@lang('Order Value')">{{ $general->cur_sym }}{{ $claim->order_amount }}</td>
                                <td data-label="@lang('Expected Cashback')">{{ $general->cur_sym }}{{ getCashback($claim->click,$claim->order_amount) }}</td>

                                <td data-label="@lang('Purchase date')">{{ showDateTime($claim->order_date) }}</td>
                                <td data-label="@lang('Click date')">{{ showDateTime($claim->click->create_date) }}</td>

                                <td data-label="@lang('Status')">
                                    @if($claim->status == 0)
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($claim->status == 1)
                                        <span class="badge badge--success">@lang('Confirmed')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.claims.details', $claim->id) }}"
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
            @if ($claims->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($claims) }}
            </div>
            @endif
        </div><!-- card end -->
    </div>
</div>


@endsection


@push('breadcrumb-plugins')
    @if(!request()->routeIs('admin.users.claims') && !request()->routeIs('admin.users.claims.method'))
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
