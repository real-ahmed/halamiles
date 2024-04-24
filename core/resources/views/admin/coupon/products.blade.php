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

                                    <th>@lang('Store')</th>


                                    <th>@lang('Status')</th>

                                    <th>@lang('Action')</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($products as $product)
                                    <tr>

                                        <td data-label="@lang('S.N')">{{ $products->firstItem() + $loop->index }}</td>

                                        <td data-label="@lang('Title')">{{ __($product->title) }}</td>

                                        <td data-label="@lang('Category')">{{ __($product->category->name) }}</td>


                                        <td data-label="@lang('Category')">{{ __($product->store->name) }}</td>




                                        <td data-label="@lang('Status')">

                                            @if ($product->status == 0)
                                                <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                            @elseif($product->status == 1 && $product->ending_date > now())
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @elseif($product->status == 1 && $product->ending_date <= now())
                                                <span
                                                    class="text--small badge font-weight-normal badge--dark">@lang('Expired')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--danger">@lang('Rejected')</span>
                                            @endif

                                        </td>

                                        <td data-label="@lang('Action')">

                                            <a href={{ route('admin.product.form', $product->id) }}
                                                class="btn btn-sm btn-outline--primary editproduct"
                                                data-id="{{ $product->id }}" data-name="{{ __($product->name) }}"
                                                data-icon="{{ $product->icon }}" data-status="{{ $product->status }}"
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

                @if ($products->hasPages())
                    <div class="card-footer py-4">

                        {{ paginateLinks($products) }}

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
                    placeholder="@lang('Search product')" value="{{ request()->search }}">

                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>



            </div>

        </form>

        <a href="{{ route('admin.product.form') }}" class="btn btn-outline--primary box--shadow1 addproduct"><i
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
