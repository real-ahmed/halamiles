@extends('admin.layouts.app')

@section('panel')

    <div class="row">


        <div class="col-lg-12">

            <div class="card b-radius--10 ">

                <div class="card-body p-0">

                    <div class="table-responsive--md  table-responsive">


                        <table class="table table--light style--two spaced-rows">

                            <thead>

                            <tr>

                                <th>@lang('S.N.')</th>

                                <th>@lang('Name')</th>

                                <th>@lang('Category')</th>

                                <th>@lang('Coupon')</th>

                                <th>@lang('Faverate')</th>

                                <th>@lang('Cashback')</th>
                                <th>@lang('User Percentage')</th>

                                @if (!request()->routeIs('admin.store.featured'))
                                    <th>@lang('Featured')</th>
                                @endif

                                <th>@lang('Status')</th>

{{--                                <th>{{ __('Marketing channels') }}</th>--}}
                                <th>{{ __('views') }}</th>


                                <th>@lang('Action')</th>

                            </tr>

                            </thead>

                            <tbody>

                            @forelse($stores as $store)
                                <tr>

                                    <td data-label="@lang('S.N')">{{ $stores->firstItem() + $loop->index }}</td>

                                    <td data-label="@lang('Name')">{{ __($store->name) }}</td>

                                    <td data-label="@lang('Username')">

                                        @if ($store->category_id)
                                            {{ $store->category->name }}
                                        @else
                                            -
                                        @endif

                                    </td>

                                    <td data-label="@lang('Coupon')">

                                        <a href="{{ route('admin.coupon.store', $store->id) }}"
                                           class="icon-btn">{{ $store->coupons->count() }}</a>

                                    </td>


                                    <td data-label="@lang('Coupon')">

                                        <a href="{{ route('admin.users.by_store', $store->id) }}"
                                           style='background: #af1515;'
                                           class="icon-btn">{{ $store->favorite->count() }}</a>

                                    </td>

                                    <td data-label="@lang('Cashback')">

                                        {{ __($store->cashback) }}{{ $store->cashbacktype->name }}

                                    </td>


                                    <td data-label="@lang('User Percentage')">

                                        {{ __((int)$store->user_percentage).'%' }}

                                    </td>

                                    @if (!request()->routeIs('admin.store.featured'))
                                        <td data-label="@lang('Featured')">

                                            @if ($store->featured == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Yes')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('No')</span>
                                            @endif

                                        </td>
                                    @endif

                                    <td data-label="@lang('Status')">

                                        @if ($store->status == 1)
                                            <span
                                                class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span
                                                class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                        @endif

                                    </td>

{{--                                    <td data-label="{{ __('Marketing channels') }}">--}}

{{--                                        @if (!empty($store->marketing_channels))--}}
{{--                                            @foreach (json_decode($store->marketing_channels) as $key => $val)--}}
{{--                                                @if ($val == 1)--}}
{{--                                                    <span--}}
{{--                                                        class="text--small badge font-weight-normal badge--success">{{ $key }}</span>--}}
{{--                                                @endif--}}
{{--                                            @endforeach--}}
{{--                                        @endif--}}

{{--                                    </td>--}}

                                    <td data-label="{{ __('views') }}">

                                        {{ $store->views }}

                                    </td>


                                    <td data-label="@lang('Action')">
                                        <button class="btn btn-outline--primary box--shadow1 addStore"
                                                data-store_id="{{ $store->id }}"
                                                data-original-title="@lang('cashback category')">
                                            <i class="las la-plus"></i>
                                            @lang('cashback category')
                                        </button>

                                        <a class="btn btn-sm btn-outline--primary"
                                           href="{{ route('admin.store.save',$store->id) }}">
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

                <div class="card-footer py-4">

                    <a href="{{ route('admin.download.csv', ['table' => 'stores', 'columns' => 'id,category_id,name,image,cashback,offer_cashback,ending_date,cashbacktype_id,description,url,terms,featured,marketing_channels,network_id,status,created_at']) }}"
                       class="btn btn-outline--primary box--shadow1">Download CSV</a>

                    @if ($stores->hasPages())
                        {{ paginateLinks($stores) }}
                    @endif

                </div>

            </div>

        </div>

    </div>




    </div>

    </div>

    {{-- Store modal --}}
    <div id="storeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group" style="display: none">
                            <label>@lang('Store')</label>
                            <select name="store_id" class="form-control" required>
                                <option value="" hidden>@lang('Select One')</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">

                            <label>@lang('Name')</label>

                            <input class="form-control" type="text" name="name" required>

                        </div>

                        <div class="form-group">

                            <label>@lang('Cashback')</label>

                            <div class="input-group">

                                <input class="form-control" type="number" step="any" name="cashback" required>

                                <select name='cashbacktype_id' style='width=65px;' class="input-group-text">

                                    @foreach ($cashbacktypes as $cashbacktype)
                                        <option value="{{ $cashbacktype->id }}">{{ __($cashbacktype->name) }}</option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                        <div class="form-group">
                            <label>@lang('user %')</label>
                            <div class="input-group">
                                <input class="form-control" type="number" step="any" name="user_percentage"
                                       value="{{ $store ? $store->getRawOriginal('user_percentage') : old('user_percentage') }}"
                                       required>
                                <span style='width=65px;' class="input-group-text">%</span>
                            </div>
                        </div>


                        <div class="form-group">

                            <label>@lang('Category Url')</label>

                            <input class="form-control" type="text" name="url" value="" required>

                        </div>
                        <div class="form-group">
                            <label class="form-control-label">@lang('Accepted Withdrawal Methods')</label>
                            <div class="scrollbox">
                                @foreach ($withdrawMethods as $key => $method)
                                    <div class="{{ $key % 2 == 0 ? 'even' : 'odd' }}">
                                        <input name="withdrawlmethod_id[]" value="{{ $method->id }}" type="checkbox"

                                        >
                                        {{ $method->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group statusGroup">

                            <label>@lang('Status')</label>

                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                   data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                   name="status">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection



@push('breadcrumb-plugins')
    <div class="sort">

        <form action="" method="GET" id="sortForm">

            <input type="hidden" name="search" value="{{ request()->query('search') }}">

            <select name="sort_by" id="column" onchange="document.getElementById('sortForm').submit()">

                <option value="created_at" @if (request()->input('sort_by') == 'created_at') selected @endif>Created
                    at
                </option>

                <option value="name" @if (request()->input('sort_by') == 'name') selected @endif>Name</option>

                <option value="updated_at" @if (request()->input('sort_by') == 'updated_at') selected @endif>Last
                    Update
                </option>

                <option value="cashback" @if (request()->input('sort_by') == 'cashback') selected @endif>Cashback
                </option>

            </select>

            <select name="sort_direction" id="order" onchange="document.getElementById('sortForm').submit()">

                <option value="desc" @if (request()->input('sort_direction') == 'desc') selected @endif>Descending
                </option>

                <option value="asc" @if (request()->input('sort_direction') == 'asc') selected @endif>Ascending</option>

            </select>

        </form>

    </div>

    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">

        <form action="" method="GET" class="header-search-form">

            <div class="input-group has_append">

                <input type="text" name="search" class="form-control bg-white text--black"
                       placeholder="@lang('Search Store')" value="{{ request()->search }}">

                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>

                <input type="hidden" name="sort_by"
                       value="{{ request()->query('sort_by') ? request()->query('sort_by') : 'created_at' }}">

                <input type="hidden" name="sort_direction"
                       value="{{ request()->query('sort_direction') ? request()->query('sort_direction') : 'desc' }}">

            </div>

        </form>

        <a class="btn btn-outline--primary box--shadow1 "
           href="{{ route('admin.store.save') }}"><i
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

        .datepickers-container {

            z-index: 1056;

        }


        @media (max-width: 400px) {

            .header-search-form {

                width: 100%
            }

        }

        .right-part {

            display: flex;

            flex-direction: row;

            align-items: center;

            justify-content: flex-end;

        }


        #sortForm {

            display: flex;

            flex-direction: row;

            align-content: center;

            align-items: center;

        }


        #sort_by,
        #sort_direction {

            font-size: 17px;

            margin-right: 20px;


        }
    </style>
@endpush













@push('script')
    <script>
        (function ($) {
            "use strict";

            var modal = $('#storeModal');
            var action = '{{ route('admin.store-category.save') }}';

            $('.addStore').click(function () {
                var data = $(this).data();

                modal.find('.modal-title').text("@lang('Add Category')");
                modal.find('.statusGroup').hide();
                modal.find('form').attr('action', action);
                modal.find('textarea[name="note"]').removeAttr('required');
                modal.find('[name="store_id"]').val(data.store_id)

                modal.modal('show');
            });

            modal.on('shown.bs.modal', function (e) {
                $(document).off('focusin.modal');
            });


            modal.on('hidden.bs.modal', function () {

                modal.find('form')[0].reset();

                modal.find('input[type=checkbox]').prop('checked', false);

            });


        })(jQuery);
    </script>
@endpush

