@extends($activeTemplate.'layouts.master')

@section('content')

<section class="pt-80 pb-80">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-12">

                <div class="table-responsive table-responsive--md">

                    <table class="table custom--table">

                        <thead>

                            <tr>

                                <th>@lang('Gateway | Transaction')</th>

                                <th class="text-center">@lang('Initiated')</th>

                                <th class="text-center">@lang('Amount')</th>

                                <!-- <th class="text-center">@lang('Conversion')</th> -->

                                <th class="text-center">@lang('Status')</th>

                                <th>@lang('Details')</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($withdraws as $withdraw)

                                <tr>

                                    <td data-label="@lang('Gateway | Transaction')">

                                        <span class="fw-bold"> <span class="text-primary">{{ __($withdraw->method?->name) }}</span> </span>

                                        <br>

                                        <small> {{ $withdraw->transaction->id }} </small>

                                    </td>



                                    <td class="text-center" data-label="@lang('Initiated')">

                                        {{ showDateTime($withdraw->created_at) }}<br>{{ diffForHumans($withdraw->created_at) }}

                                    </td>

                                    <td class="text-center" data-label="@lang('Amount')">

                                        {{ __($withdraw->method->symbol) }}{{ showAmount($withdraw->amount ) }} - <span class="text-danger" title="@lang('charge')">{{showAmount($withdraw->charge)}}</span>

                                        <br>

                                        <strong title="@lang('Amount with charge')">
                                        {{ __($withdraw->method->symbol) }}{{showAmount($withdraw->finalAmount)}}
                                        </strong>

                                    </td>


                                    <td class="text-center" data-label="@lang('Status')">

                                            @if($withdraw->transaction->status == 0)
                                                <span class="badge badge--warning">@lang('Pending')</span>
                                            @elseif($withdraw->transaction->status == 1)
                                                <span class="badge badge--success">@lang('Confirmed')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Rejected')</span>
                                            @endif

                                    </td>

                                    @php

                                        $details = ($withdraw->data != null) ? json_encode($withdraw->data) : null;

                                    @endphp



                                    <td data-label="@lang('Details')">
                                        @if($details)
                                        <a href="javascript:void(0)" class="icon-btn btn--base  detailBtn "


                                            data-info="[{{ $details }}]"

                                            data-bs-toggle="tooltip"

                                            data-bs-position="top" title="@lang('View Details')"

                                            >
                                       
                                            <i class="las la-desktop"></i>

                                        </a>
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

            </div>

            {{ $withdraws->links() }}

        </div>

    </div>

</section>



{{-- APPROVE MODAL --}}

<div id="detailModal" class="modal fade" tabindex="-1" role="dialog">

    <div class="modal-dialog" role="document">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">@lang('Details')</h5>

                <span type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                    <i class="las la-times"></i>

                </span>

            </div>

            <div class="modal-body">

                <ul class="list-group userData mb-2">

                </ul>

                <div class="feedback"></div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal">@lang('Close')</button>

            </div>

        </div>

    </div>

</div>

@endsection



@push('script')

    <script>

        (function ($) {

            "use strict";

            $('.detailBtn').on('click', function () {

                var modal = $('#detailModal');



                var userDataRaw = $(this).data('info');
                var userData;
                var html = '';

                try {
                    userData = JSON.parse(userDataRaw);
                } catch (e) {
                    console.error("Error parsing userData:", e);
                    return;  // Exit the function if parsing fails
                }

                if (userData) {
                    Object.entries(userData).forEach(([key, value]) => {
                        html += `
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>${key}</span>
                            <span>${value}</span>
                        </li>`;
                    });
                }



                modal.find('.userData').html(html);



                if($(this).data('admin_feedback') != undefined){

                    var adminFeedback = `

                        <div class="my-3">

                            <strong>@lang('Admin Feedback')</strong>

                            <p>${$(this).data('admin_feedback')}</p>

                        </div>

                    `;

                }else{

                    var adminFeedback = '';

                }



                modal.find('.feedback').html(adminFeedback);





                modal.modal('show');

            });

        })(jQuery);

    </script>

@endpush

