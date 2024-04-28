@extends('admin.layouts.app')



@section('panel')
    <div class="row justify-content-center">

        @if (request()->routeIs('admin.transactions.all'))
            <div class="col-xxl-3 col-sm-6 mb-30">

                <div class="widget-two box--shadow2 b-radius--5 bg--6 has-link">

                    <a href="{{ route('admin.transactions.all') }}?search={{ request('search', '') }}" class="item-link"></a>

                    <div class="widget-two__content">

                        <h2 class="text-white">{{ $total }}</h2>

                        <p class="text-white">@lang('Total Transactions')</p>

                    </div>

                </div><!-- widget-two end -->

            </div>
            <div class="col-xxl-3 col-sm-6 mb-30">

                <div class="widget-two box--shadow2 b-radius--5 bg--success has-link">

                    <a href="{{ route('admin.transactions.confirmed') }}?search={{ request('search', '') }}"
                        class="item-link"></a>

                    <div class="widget-two__content">

                        <h2 class="text-white">{{ $confirmed }}</h2>

                        <p class="text-white">@lang('Confirmed Transactions')</p>



                    </div>

                </div><!-- widget-two end -->

            </div>




            <div class="col-xxl-3 col-sm-6 mb-30">

                <div class="widget-two box--shadow2 has-link b-radius--5 bg--dark">

                    <a href="{{ route('admin.transactions.pending') }}?search={{ request('search', '') }}"
                        class="item-link"></a>

                    <div class="widget-two__content">

                        <h2 class="text-white">{{ $pending }}</h2>

                        <p class="text-white">@lang('Pending Transactions')</p>

                    </div>


                </div><!-- widget-two end -->

            </div>


            <div class="col-xxl-3 col-sm-6 mb-30">

                <div class="widget-two box--shadow2 has-link b-radius--5 bg--pink">

                    <a href="{{ route('admin.transactions.cancelled') }}?search={{ request('search', '') }}"
                        class="item-link"></a>

                    <div class="widget-two__content">
                        <h2 class="text-white">{{ $cancelled }}</h2>

                        <p class="text-white">@lang('Cancelled Transactions')</p>


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

                                    <th>@lang('Transaction')</th>

                                    <th>@lang('Title')</th>

                                    <th>@lang('Click ID')</th>

                                    <th>@lang('Date')</th>

                                    <th>@lang('rate/category')</th>

                                    <th>@lang('User')</th>

                                    <th>@lang('Amount')</th>

                                    <th>@lang('Type')</th>

                                    <th>@lang('Status')</th>

                                    <th>@lang('Action')</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($transactions as $transaction)
                                    <tr>

                                        <td data-label="@lang('Transactions')">

                                            <span class="fw-bold"> {{ $transaction->id }}</span>

                                        </td>

                                        <td data-label="@lang('Title')">

                                            {{ $transaction->title }}

                                        </td>

                                        <td data-label="@lang('Click ID')">

                                            {{$transaction->clickTransaction?->click_id ?? '-' }}

                                        </td>

                                        <td data-label="@lang('Date')">

                                            {{ showDateTime($transaction->created_at) }}<br>{{ diffForHumans($transaction->created_at) }}

                                        </td>


                                        <td data-label="@lang('rate/category')">

                                            {{$transaction->clickTransaction?->category_rate ?? '-' }}

                                        </td>

                                        <td data-label="@lang('Referral User')">

                                            <span
                                                class="fw-bold">{{ $transaction->user ? $transaction->user->fullname : '' }}</span>

                                            <br>

                                            <span class="small">

                                                <a
                                                    href="{{ $transaction->user ? route('admin.users.detail', $transaction->user->id) : '' }}"><span>{{ $transaction->user ? '@' : '' }}</span>{{ $transaction->user ? $transaction->user->username : '' }}</a>

                                            </span>

                                        </td>

                                        <td data-label="@lang('Referral Amount')">

                                            {{ $transaction->amount }}

                                        </td>


                                        <td data-label="@lang('Referral Amount')">

                                            {{ $transaction->type }}

                                        </td>




                                        <td data-label="@lang('status')">

                                            @if ($transaction->status == 0)
                                                <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                            @elseif($transaction->status == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Cancelled')</span>
                                            @endif


                                        </td>


                                        <td data-label="@lang('Action')">

                                            <a href="{{ route('admin.transactions.details', $transaction->id) }}"
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

                @if ($transactions->hasPages())
                    <div class="card-footer py-4">

                        {{ paginateLinks($transactions) }}

                    </div>
                @endif

            </div><!-- card end -->

        </div>

    </div>
@endsection





@push('breadcrumb-plugins')

    @if (!request()->routeIs('admin.users.Transactions') && !request()->routeIs('admin.users.Transactions.method'))
        <form action="" method="GET">

            <div class="form-inline float-sm-end mb-2 ms-0 ms-xl-2 ms-lg-0">

                <div class="input-group">

                    <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Trx number/Username')"
                        value="{{ request()->search ?? '' }}">

                    <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>

                </div>

            </div>

            <div class="form-inline float-sm-end">

                <div class="input-group">

                    <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - "
                        data-language="en" class="datepicker-here form-control bg--white" data-position='bottom right'
                        placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">

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
        (function($) {

            "use strict";

            if (!$('.datepicker-here').val()) {

                $('.datepicker-here').datepicker();

            }

        })(jQuery)
    </script>
@endpush
