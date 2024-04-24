@extends($activeTemplate . 'layouts.master')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="card custom--card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.clicks.claim.confirm') }}" class="row"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="issue" class="form-label">@lang('What is the issue ?')</label>
                                    <select name="issue_id" class="form-select form--control" required>
                                        <option value="" hidden>@lang('Select One')</option>
                                        @foreach ($issues as $issue)
                                            <option value="{{ $issue->id }}">{{ $issue->issue }} ({{ $issue->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Copoun or Store')</label>
                                    <input type="text" readonly name="model" value="{{ $click->model->title }}"
                                        class="form-control form--control" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">@lang('Date and time of purchase')</label>
                                    <input type="text" name="order_date" id="ending_date" value=""
                                        class="form-control form--control" autocomplete="off" required>
                                </div>



                                <div class="form-group">
                                    <label for="" class="form-label">@lang('Purchase amount')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="order_amount" value=""
                                            class="form-control form--control" required>
                                        <span class="input-group-text bg--base text-white">{{ $general->cur_sym }}</span>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <input type="text" name='click_id' hidden value="{{ $click->id }}">
                                    <button type="submit" class="btn btn--base w-100">
                                        @lang('Next')
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style>
        .profile-thumb,
        .profile-thumb .profilePicPreview {
            width: 280px;
            height: 190px;
        }

        @media (max-width:450px) {

            .profile-thumb,
            .profile-thumb .profilePicPreview {
                width: 250px;
                height: 175px;
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
        (function($) {
            "use strict";
            var start = new Date(),
                prevDay,
                startHours = 0;

            start.setHours(0);
            start.setMinutes(0);

            if ([6, 0].indexOf(start.getDay()) != -1) {
                start.setHours(10);
                startHours = 10
            }
            $('#ending_date').datepicker({
                timepicker: true,
                language: 'en',
                dateFormat: 'dd-mm-yyyy',
                startDate: start,
                minHours: startHours,
                maxHours: 23,
                onSelect: function(fd, d, picker) {
                    if (!d) return;

                    var day = d.getDay();

                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    if (day == 6 || day == 0) {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    } else {
                        picker.update({
                            minHours: 0,
                            maxHours: 23
                        })
                    }
                }
            });

        })(jQuery);
    </script>
@endpush
