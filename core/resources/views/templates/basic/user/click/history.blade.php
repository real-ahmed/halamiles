@extends($activeTemplate . 'layouts.master')

@section('content')
    <section class="pt-80 pb-80 sections">

        <div class="container">
            <div class="alert alert-warning" role="alert">
                @lang('You cannot request a lost claim until after a week has passed, and if more than three weeks have passed, you cannot claim it and you can only claim it once.')
            </div>
            <div class="text-end">
                <a href="{{ route('user.clicks.claims') }}" class="btn btn-sm btn--base mb-2 add-store"> <i
                        class="fa fa-history"></i> @lang('Claims History')</a>
            </div>
            <div class="row justify-content-center">

                <div class="col-md-12">

                    <div class="table-responsive table-responsive--md">

                        <table class="table custom--table">

                            <thead>

                                <tr>

                                    <th>@lang('Click id')</th>

                                    <th class="text-center">@lang('Store/Coupon')</th>

                                    <th class="text-center">@lang('Date')</th>

                                    <!-- <th class="text-center">@lang('Conversion')</th> -->

                                    <th class="text-center">@lang('Type')</th>

                                    <th>@lang('Actions')</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($clicks as $click)
                                    <tr>

                                        <td data-label="@lang('Click id')">

                                            <span class="fw-bold"> <span class="text-primary">{{ __($click->id) }}</span>
                                            </span>


                                        </td>


                                        <td class="text-center" data-label="@lang('Store/Coupon')">

                                            {{ __($click->model->title) }}

                                        </td>


                                        <td class="text-center" data-label="@lang('Date')">

                                            {{ showDateTime($click->created_at) }}<br>{{ diffForHumans($click->created_at) }}

                                        </td>



                                        <td class="text-center" data-label="@lang('Type')">

                                            {{ __($click->type) }}

                                        </td>



                                        <td data-label="@lang('Request Missing Claim')">

                                            @if ($click->IsClaim)
                                                <a href="{{ route('user.clicks.claim', $click->id) }}"
                                                    class="btn-sm btn--base">
                                                    @lang('Request Missing Claim')
                                                </a>
                                            @else
                                                <span class="btn-sm btn--base disabled">
                                                    @lang('Request Missing Claim')
                                                </span>
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

                {{ $clicks->links() }}

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
        (function($) {

            "use strict";

            $('.detailBtn').on('click', function() {

                var modal = $('#detailModal');



                var userDataRaw = $(this).data('info');
                var userData;
                var html = '';

                try {
                    userData = JSON.parse(userDataRaw);
                } catch (e) {
                    console.error("Error parsing userData:", e);
                    return; // Exit the function if parsing fails
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



                if ($(this).data('admin_feedback') != undefined) {

                    var adminFeedback = `

                        <div class="my-3">

                            <strong>@lang('Admin Feedback')</strong>

                            <p>${$(this).data('admin_feedback')}</p>

                        </div>

                    `;

                } else {

                    var adminFeedback = '';

                }



                modal.find('.feedback').html(adminFeedback);





                modal.modal('show');

            });

        })(jQuery);
    </script>
@endpush
