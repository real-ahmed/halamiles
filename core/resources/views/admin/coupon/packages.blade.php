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
                                <th>@lang('Name')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($packages as $package)
                            <tr>
                                <td data-label="@lang('S.N')">{{ $packages->firstItem() + $loop->index }}</td>
                                <td data-label="@lang('Name')">{{ __($package->name) }}</td>
                                <td data-label="@lang('Duration')">{{ $package->duration }} @lang('Days')</td>
                                <td data-label="@lang('Price')">{{ $general->cur_sym }}{{ showAmount($package->price) }}</td>
                                <td data-label="@lang('Status')">
                                    @if($package->status == 1)
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                    @else
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <button class="btn btn-sm btn-outline--primary edit-btn" data-package="{{ $package }}" data-toggle="tooltip"  data-original-title="@lang('Edit')">
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
                        </table><!-- table end -->
                    </div>
                </div>
                @if($packages->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($packages) }}
                </div>
                @endif
            </div>
        </div>
    </div>


{{-- Package modal --}}
<div id="packageModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Name')<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('Duration')<span class="text-danger">*</span></label>
                        <div class="input-group has_append">
                            <input type="number" min="0" name="duration" class="form-control">
                            <span class="input-group-text">@lang('Days')</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>@lang('Price')<span class="text-danger">*</span></label>
                        <div class="input-group has_append">
                            <input type="number" step="any" min="0" name="price" class="form-control" autocomplete="off">
                            <span class="input-group-text">{{ __($general->cur_text) }}</span>
                        </div>
                    </div>
                    <div class="form-group statusGroup">
                        <label>@lang('Status')</label>
                        <input type="checkbox" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" data-width="100%" name="status">
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
    <button class="btn btn-sm btn-outline--primary add-package"><i class="las la-plus"></i>@lang('Add New')</button>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";
            var action = `{{ route('admin.package.save') }}`;
            var modal = $('#packageModal');

            $('.add-package').on('click', function(){
                modal.find('.modal-title').text(`@lang('Add New Package')`);
                modal.find('.statusGroup').hide();
                modal.find('form').attr('action', action);
                modal.modal('show');
            });

            $('.edit-btn').on('click', function(){
                var pack = $(this).data('package');
                modal.find('.modal-title').text(`@lang('Update Package')`);
                modal.find('[name=name]').val(pack.name);
                modal.find('[name=duration]').val(pack.duration);
                modal.find('[name=price]').val(parseFloat(pack.price).toFixed(2));
                modal.find('.statusGroup').show();
                if(pack.status == 1){
                    modal.find('input[name=status]').bootstrapToggle('on');
                }else{
                    modal.find('input[name=status]').bootstrapToggle('off');
                }
                modal.find('form').attr('action', `${action}/${pack.id}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
