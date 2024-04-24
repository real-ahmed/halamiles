@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-80 pb-80">
        <div class="container">
            <div class="text-end">
                <button class="btn btn-sm btn--base mb-2 add-store"> <i class="fa fa-plus"></i> @lang('Add New')</button>
            </div>
            <div class="table-responsive table-responsive--md">
                <table class="table custom--table">
                    <thead>
                        <tr>
                            <th>@lang('S.N.')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Coupon')</th>
                            <th>@lang('Featured')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stores as $store)
                            <tr>
                                <td data-label="@lang('S.N.')">{{ $stores->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('Name')">{{ __($store->name) }}</td>
                                <td data-label="@lang('Coupon')">{{ $store->coupons->count() }}</td>
                                <td data-label="@lang('Featured')">
                                    @if ($store->featured == 1)
                                        <span class="badge badge--success">@lang('Yes')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('No')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Status')">
                                    @if ($store->status == 1)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="Action">
                                    <button class="icon-btn btn--base ms-1 edit-store" data-id="{{ $store->id }}" data-name="{{ $store->name }}" data-status="{{ $store->status }}"
                                        data-image="{{ getImage(getFilePath('store') . '/' . @$store->image) }}"><i
                                            class="las la-pen"></i></button>
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
            {{ $stores->links() }}
        </div>
    </section>

    <div id="storeModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close btn--danger" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    @csrf
                <div class="modal-body">
                    <div class="row">

                        <div class="form-group">
                            <label for="email" class="form-label">@lang('Store Name')</label>
                            <input type="text" name="name" class="form-control form--control" required>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">@lang('Image')</label>
                            <div class="profile-thumb-wrapper text-center">
                                <div class="profile-thumb">
                                    <div class="avatar-preview">
                                        <div class="profilePicPreview"
                                            style="background-image: url({{ getImage(getFilePath('store')) }})">
                                        </div>
                                    </div>
                                    <div class="avatar-edit" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="@lang('Upload Image')">
                                        <input type="file" class="profilePicUpload" name="image"
                                            accept=".jpg, .jpeg, .png" />
                                        <label for="image"><i class="las la-upload"></i> @lang('Update')</label>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'),
                                        @lang('jpg'), @lang('png').</b> <br> </small>
                            </div>
                        </div>
                        <div class="form-group statusGroup">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')"
                                name="status" @if (@$store->status) checked @endif>
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--base w-100">
                            @lang('Submit')
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@push('style')
    <style>
        .profile-thumb,
        .profile-thumb .profilePicPreview {
            width: 25rem;
            height: 12.5rem;
        }

        @media (max-width:450px) {

            .profile-thumb,
            .profile-thumb .profilePicPreview {
                width: 16.5rem;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            var defaultImage = '{{ getImage(getFilePath('store')) }}';
            var action = `{{ route('user.store.save') }}`
            $('.add-store').on('click', function() {
                var modal = $('#storeModal');
                modal.find('.modal-title').text(`@lang('Add Store')`);
                modal.find('.profilePicPreview').css('background-image', `url(${defaultImage})`);
                modal.find('form').attr('action', action);
                modal.find('.statusGroup').hide();
                modal.modal('show');
            });

            $('.edit-store').on('click', function() {
                var modal = $('#storeModal');
                var data = $(this).data();
                console.log(data);
                modal.find('.modal-title').text(`@lang('Update Store')`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=cash_back]').val(data.cashback);
                modal.find('.profilePicPreview').css('background-image', `url(${data.image})`);
                modal.find('form').attr('action', action+`/${data.id}`);

                if(data.status == 1){
                    modal.find('input[name=status]').bootstrapToggle('on');
                }else{
                    modal.find('input[name=status]').bootstrapToggle('off');
                }

                modal.find('.statusGroup').show();
                modal.modal('show');
            });

            $('#storeModal').on('hidden.bs.modal', function() {
                $('#storeModal form')[0].reset();
            });

        })(jQuery);
    </script>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/bootstrap-toggle.min.js') }}"></script>
@endpush
