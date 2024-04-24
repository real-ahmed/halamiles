@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">

            <div class="card b-radius--10 ">

                <div class="card-body p-0">

                    <div class="table-responsive--md  table-responsive">

                        <table class="table table--light style--two">

                            <thead>

                                <tr>

                                    <th>@lang('S.N.')</th>

                                    <th>@lang('Title')</th>

                                    <th>@lang('Category')</th>

                                    <th>@lang('Coupon Code')</th>


                                    <th>@lang('Status')</th>

                                    <th>@lang('Action')</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($coupons as $coupon)
                                    <tr>

                                        <td data-label="@lang('S.N')">{{ $coupons->firstItem() + $loop->index }}</td>

                                        <td data-label="@lang('Title')">{{ __($coupon->title) }}</td>

                                        <td data-label="@lang('Category')">{{ __($coupon->category->name) }}</td>

                                        <td data-label="@lang('Coupon Code')">{{ $coupon->coupon_code ?? '-' }}</td>


                                        <td data-label="@lang('Status')">

                                            @if ($coupon->status == 0)
                                                <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                            @elseif($coupon->status == 1 && $coupon->ending_date > now())
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @elseif($coupon->status == 1 && $coupon->ending_date <= now())
                                                <span
                                                    class="text--small badge font-weight-normal badge--dark">@lang('Expired')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Rejected')</span>
                                            @endif

                                        </td>

                                        <td data-label="@lang('Action')">

                                            <a href={{ route('admin.coupon.form', $coupon->id) }}
                                                class="btn btn-sm btn-outline--primary editcoupon"
                                                data-id="{{ $coupon->id }}" data-name="{{ __($coupon->name) }}"
                                                data-icon="{{ $coupon->icon }}" data-status="{{ $coupon->status }}"
                                                data-toggle="tooltip" data-original-title="@lang('Edit')">

                                                <i class="las la-pen text-shadow"></i> @lang('Edit')

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

                @if ($coupons->hasPages())
                    <div class="card-footer py-4">

                        {{ paginateLinks($coupons) }}

                    </div>
                @endif

            </div>

        </div>

    </div>
@endsection



@push('breadcrumb-plugins')
    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">

        <form action="" method="GET" class="header-search-form">



            <div class="input-group has_append">

                <input type="text" name="search" class="form-control bg-white text--black"
                    placeholder="@lang('Search Coupon')" value="{{ request()->search }}">

                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>



            </div>

        </form>

        <a href="{{ route('admin.coupon.form') }}" class="btn btn-outline--primary box--shadow1 addcoupon"><i
                class="las la-plus"></i>@lang('Add New')</a>

    </div>
@endpush



@push('style')
    <style>
        .btn {

            display: inline-flex;

            justify-content: center;

            align-items: center
        }

        .header-search-wrapper {

            gap: 15px
        }

        @media (max-width:400px) {

            .header-search-form {

                width: 100%
            }

        }
    </style>
@endpush



@push('script')
    <script>
        (function($) {

            "use strict";



        })(jQuery);
    </script>
@endpush
