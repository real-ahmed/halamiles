@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two spaced-rows">
                            <thead>
                            <tr>
                                <th>@lang('S.N.')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Store')</th>
                                <th>@lang('Cashback')</th>
                                <th>@lang('User Percentage')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td data-label="@lang('S.N')">{{ $categories->firstItem() + $loop->index }}</td>
                                    <td data-label="@lang('Name')">{{ __($category->name) }}</td>
                                    <td data-label="@lang('Store')">{{ __($category->store->name) }}</td>
                                    <td data-label="@lang('Cashback')">
                                        {{ __($category->cashback) }}{{ $category->cashbacktype->name }}</td>
                                    <td data-label="@lang('User Percentage')">

                                        {{ __($category->user_percentage) }}

                                    </td>
                                    <td data-label="@lang('Status')">
                                        @if ($category->status == 1)
                                            <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                        @else
                                            <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                        @endif
                                    </td>
                                    <td data-label="@lang('Action')">
                                        <button class="btn btn-sm btn-outline--primary editStore"
                                                data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                                data-status="{{ $category->status }}"
                                                data-cashback="{{ $category->cashback }}"
                                                data-cashbacktype_id="{{ $category->cashbacktype->id }}"
                                                data-url="{{ $category->url }}"
                                                data-store_id="{{ $category->store_id }}"
                                                data-toggle="tooltip" data-original-title="@lang('Edit')">
                                            <i class="las la-pen text-shadow"></i> @lang('Edit')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer py-4"></div>
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

                        <div class="form-group">
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
                    </div>


                    <div class="form-group">

                        <label>@lang('Category Url')</label>

                        <input class="form-control" type="text" name="url" value="" required>

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
    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">

        <form action="" method="GET" class="header-search-form">

            <div class="input-group has_append">

                <input type="text" name="search" class="form-control bg-white text--black"
                       placeholder="@lang('Search Store')" value="{{ request()->search }}">

                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>

            </div>

        </form>

        <button class="btn btn-outline--primary box--shadow1 addStore"><i
                    class="las la-plus"></i>@lang('Add New')</button>

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

            var modal = $('#storeModal');
            var action = '{{ route('admin.store-category.save') }}';

            $('.addStore').click(function () {
                modal.find('.modal-title').text("@lang('Add Category')");
                modal.find('.statusGroup').hide();
                modal.find('form').attr('action', action);
                modal.find('textarea[name="note"]').removeAttr('required');
                modal.modal('show');
            });

            modal.on('shown.bs.modal', function (e) {
                $(document).off('focusin.modal');
            });

            $('.editStore').click(function () {
                var data = $(this).data();
                modal.find('.modal-title').text("@lang('Update Category')");
                modal.find('.statusGroup').show();
                modal.find('[name="store_id"]').val(data.store_id)
                modal.find('[name=name]').val(data.name);
                modal.find('[name="cashback"]').val(data.cashback)
                modal.find('[name="url"]').val(data.url)
                modal.find('[name="cashbacktype_id"]').val(data.cashbacktype_id);

                if (data.status == 1) {
                    modal.find('input[name=status]').bootstrapToggle('on');
                } else {
                    modal.find('input[name=status]').bootstrapToggle('off');
                }

                modal.find('form').attr('action', action + '/' + data.id);
                modal.modal('show');
            });


            modal.on('hidden.bs.modal', function() {

                modal.find('form')[0].reset();

                modal.find('input[type=checkbox]').prop('checked', false);

            });


        })(jQuery);
    </script>
@endpush
