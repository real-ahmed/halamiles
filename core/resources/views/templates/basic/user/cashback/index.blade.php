@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="table-responsive table-responsive--md">
        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Title')">{{ __($transaction->title) }}</td>
                                        <td data-label="@lang('Category')">{{ __($transaction->category) }}</td>
                                        <td data-label="@lang('Amount')">{{ $general->cur_sym }}{{ $transaction->amount }}</td>
                                        <td data-label="@lang('Status')">
                                            @if($transaction->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($transaction->status == 1)
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif
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
        {{ $transactions->links() }}
    </div>
    <x-confirmation-modal btnSize="btn-sm" btnBase="btn--base"></x-confirmation-modal>
</section>

<div id="reasonModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Rejected Reason')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="reason"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function($) {
        "use strict";
            $('.reasonBtn').on('click', function(){
                var modal   = $('#reasonModal');
                modal.find('.reason').text($(this).data('reason'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
