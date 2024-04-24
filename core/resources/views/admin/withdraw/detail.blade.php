@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30 justify-content-center">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Payment Via') {{ __(@$withdraw->method->name) }}</h5>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Date')
                            <span class="fw-bold">{{ showDateTime($withdraw->created_at) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="fw-bold">
                                <a href="{{ route('admin.users.detail', $withdraw->transaction->user_id) }}">{{ @$withdraw->transaction->user->username }}</a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Method')
                            <span class="fw-bold">{{ __(@$withdraw->method->name) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="fw-bold">{{ showAmount($withdraw->amount ) }} {{ __($withdraw->method->currency) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Charge')
                            <span class="fw-bold text-danger">{{ showAmount($withdraw->charge ) }} {{ __($withdraw->method->currency) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Final Amount')
                            <span class="fw-bold">{{ showAmount($withdraw->finalAmount ) }} {{ __($withdraw->method->currency) }}</span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($withdraw->transaction->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($withdraw->transaction->status == 1)
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif
                        </li>

                        @if($withdraw->admin_feedback)

                        <li class="list-group-item">

                            <strong>@lang('Admin Response')</strong>

                            <br>

                            <p>{{__($withdraw->admin_feedback)}}</p>

                        </li>

                        @endif

                    </ul>
                </div>
            </div>
        </div>
        @if($details || $withdraw->transaction->status == 0)
        <div class="col-xl-8 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('User Payment Information')</h5>
                    @if($details != null)
                        @foreach(json_decode(json_decode($details)) as $key => $val)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>{{ $key }}</h6>
                                    <p>{{ $val }}</p>
                                </div>
                            </div>
                        @endforeach
                        <!-- include('admin.withdraw.gateway_data',['details'=>json_decode($details)]) -->
                    @endif
                    @if($withdraw->transaction->status == 0)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn--success ms-1 confirmationBtn"
                                data-action="{{ route('admin.withdraw.approve', $withdraw->id) }}"
                                data-question="@lang('Are you sure to approve this transaction?')"
                                ><i class="fas fa-check"></i>
                                    @lang('Approve')
                                </button>

                                <button class="btn btn--danger ms-1 rejectBtn"
                                        data-id="{{ $withdraw->id }}"
                                        data-info="{{$details}}"
                                        data-amount="{{ showAmount($withdraw->transaction->amount)}} {{ __($general->cur_text) }}"
                                        data-username="{{ @$withdraw->transaction->user->username }}"><i class="fas fa-ban"></i> @lang('Reject')
                                </button>
                            </div>
                        </div>
                    @endif
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
                    <h5 class="modal-title">@lang('Reject Withdraw Confirmation')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.withdraw.reject')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="fw-bold">@lang('reject')</span> <span class="fw-bold withdraw-amount text-success"></span> @lang('withdraw of') <span class="fw-bold withdraw-user"></span>?</p>

                        <div class="form-group">
                            <label class="fw-bold mt-2">@lang('Reason for Rejection')</label>
                            <textarea name="message" maxlength="255" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name='banned' value = "1"></input>
                            <label for='banned' class="">@lang('Banned Withdraw')</label>
                            
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
        (function ($) {
            "use strict";

            $('.rejectBtn').on('click', function () {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-user').text($(this).data('username'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
