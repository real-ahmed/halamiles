@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Claim id')
                            <span class="fw-bold">{{ __($claim->id) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Store/Coupon')
                            <span class="fw-bold">{{ __($claim->click->model->title) }}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Claim Type')
                            <span class="fw-bold">{{ __($claim->issue->type) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Order Value')
                            <span class="fw-bold">{{ $general->cur_sym }}{{ $claim->order_amount }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Expected Cashback')
                            <span
                                class="fw-bold">{{ $general->cur_sym }}{{ getCashback($claim->click, $claim->order_amount) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="fw-bold">
                                <a
                                    href="{{ route('admin.users.detail', $claim->click->user->id) }}">{{ @$claim->click->user->username }}</a>
                            </span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('vouchers/codes')
                            <span class="fw-bold">{{ __($claim->code) }}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('another link')
                            <span class="fw-bold">{{ __($claim->another_link) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('browser')
                            <span class="fw-bold">{{ $claim->private_browser ? 'Yes' : 'No' }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('VPN')
                            <span class="fw-bold">{{ $claim->vpn ? 'Yes' : 'No' }}</span>
                        </li>



                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Purchase date')
                            <span class="fw-bold">{{ showDateTime($claim->order_date) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Click date')
                            <span class="fw-bold">{{ showDateTime($claim->click->create_date) }}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if ($claim->status == 0)
                                <span class="badge badge--warning">@lang('Pending')</span>
                            @elseif($claim->status == 1)
                                <span class="badge badge--success">@lang('Confirmed')</span>
                            @else
                                <span class="badge badge--danger">@lang('Rejected')</span>
                            @endif
                        </li>

                        @if ($claim->admin_feedback)
                            <li class="list-group-item">

                                <strong>@lang('Admin Response')</strong>

                                <br>

                                <p>{{ __($claim->admin_feedback) }}</p>

                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
        @if ($claim->status == 0)
            <div class="col-xl-8 col-md-6 mb-30">
                <div class="card b-radius--10 overflow-hidden box--shadow1">
                    <div class="card-body">
                        <h5 class="card-title mb-50 border-bottom pb-2">@lang('User Claim Information')</h5>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn--success ms-1 approveBtn" data-id="{{ $claim->id }}"
                                    data-username=""><i class="fas fa-check"></i>
                                    @lang('Approve')
                                </button>

                                <button class="btn btn--danger ms-1 rejectBtn" data-id="{{ $claim->id }}"
                                    data-username=""><i class="fas fa-ban"></i> @lang('Reject')
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Claim Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.claims.reject') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="fw-bold">@lang('reject')</span> @lang('Athis claim ?')</p>

                        <div class="form-group">
                            <label class="fw-bold mt-2">@lang('Reason for Rejection')</label>
                            <textarea name="message" maxlength="255" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Approve Claim Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.claims.approve', $claim->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="fw-bold">@lang('approve')</span> @lang('Athis claim ?')</p>

                        <div class="form-group">
                            <label class="fw-bold mt-2">@lang('CashBack Amount')</label>
                            <input name="cashback" class="form-control"
                                value="{{ getCashback($claim->click, $claim->order_amount) }}" required>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('.rejectBtn').on('click', function() {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });



            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
