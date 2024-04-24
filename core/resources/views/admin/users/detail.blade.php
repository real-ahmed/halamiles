@extends('admin.layouts.app')



@section('panel')
    <div class="row">

        <div class="col-12">
            <div class="row gy-4">
                <div class="col-xxl-3 col-sm-6">

                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--primary">

                        <div class="widget-two__icon b-radius--5 bg--primary">

                            <i class="las la-dollar-sign"></i>

                        </div>

                        <div class="widget-two__content">

                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($totalDeposit) }}</h3>

                            <p class="text-white">@lang('Payments')</p>

                        </div>

                        <a href="{{ route('admin.deposit.list') }}?search={{ $user->username }}"
                            class="widget-two__btn">@lang('View All')</a>

                    </div>

                </div>





                <div class="col-xxl-3 col-sm-6">

                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">

                        <div class="widget-two__icon b-radius--5 bg--primary">

                            <i class="las la-user-friends"></i>

                        </div>

                        <div class="widget-two__content">

                            <h3 class="text-white">{{ $user->referrals->count() }}</h3>

                            <p class="text-white">@lang('Referrals')</p>

                        </div>

                        <a href="{{route('admin.referrals.all')}}?search={{$user->username}}" class="widget-two__btn">@lang('View All')</a>

                    </div>

                </div>



                <div class="col-xxl-3 col-sm-6">

                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--19">

                        <div class="widget-two__icon b-radius--5 bg--primary">

                            <i class="las la-heart"></i>

                        </div>

                        <div class="widget-two__content">

                            <h3 class="text-white">{{ $user->favorite->count() }}</h3>

                            <p class="text-white">@lang('Favorite Stores')</p>

                        </div>

                        <a href="{{ route('admin.store.user.favorite', $user->id) }}"
                            class="widget-two__btn">@lang('View All')</a>

                    </div>

                </div>


                <!-- dashboard-w1 end -->


                <div class="col-xxl-3 col-sm-6">

                    <div class="widget-two style--two box--shadow2 b-radius--5 bg--1">

                        <div class="widget-two__icon b-radius--5 bg--primary">

                            <i class="las la-wallet"></i>

                        </div>

                        <div class="widget-two__content">

                            <h3 class="text-white">{{ $general->cur_sym }}{{ showAmount($user->balance)  }}</h3>

                            <p class="text-white">@lang('Balance')</p>

                        </div>

                        <a href="{{route('admin.transactions.all')}}?search={{$user->username}}" class="widget-two__btn">@lang('View All')</a>

                    </div>

                </div>

                <!-- dashboard-w1 end -->

            </div>




            <div class="d-flex flex-wrap gap-3 mt-4">



                <div class="flex-fill">

                    <a href="{{ route('admin.report.login.history') }}?search={{ $user->username }}"
                        class="btn btn--primary btn--shadow w-100 btn-lg">

                        <i class="las la-list-alt"></i>@lang('Logins')

                    </a>

                </div>



                <div class="flex-fill">

                    <a href="{{ route('admin.users.notification.log', $user->id) }}"
                        class="btn btn--secondary btn--shadow w-100 btn-lg">

                        <i class="las la-bell"></i>@lang('Notifications')

                    </a>

                </div>



                <div class="flex-fill">

                    <a href="{{ route('admin.users.login', $user->id) }}" target="_blank"
                        class="btn btn--primary btn--gradi btn--shadow w-100 btn-lg">

                        <i class="las la-sign-in-alt"></i>@lang('Login as User')

                    </a>

                </div>








                <div class="flex-fill">
                    <button type="button" class="btn btn--secondary btn--shadow w-100 btn-lg bal-btn"
                        data-bs-toggle="modal" data-bs-target="#addSubModal">

                        <i class="las la-dollar-sign"></i></i>@lang('Send mony')

                    </button>

                </div>


                <div class="flex-fill">
                    <button type="button" class="btn btn--primary btn--gradi btn--shadow w-100 btn-lg"
                        data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="las la-unlock-alt"></i>@lang('reset password')

                    </button>

                </div>
                <div class="flex-fill">

                    @if ($user->status == 1)
                        <button type="button" class="btn btn--warning btn--gradi btn--shadow w-100 btn-lg userStatus"
                            data-bs-toggle="modal" data-bs-target="#userStatusModal">

                            <i class="las la-ban"></i>@lang('Ban User')

                        </button>
                    @else
                        <button type="button" class="btn btn--success btn--gradi btn--shadow w-100 btn-lg userStatus"
                            data-bs-toggle="modal" data-bs-target="#userStatusModal">

                            <i class="las la-undo"></i>@lang('Unban User')

                        </button>
                    @endif

                </div>
            </div>





            <div class="card mt-30">

                <div class="card-header">

                    <h5 class="card-title mb-0">@lang('Information of') {{ $user->fullname }}</h5>

                </div>

                <div class="card-body">

                    <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST"
                        enctype="multipart/form-data">

                        @csrf



                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group ">

                                    <label>@lang('First Name')</label>

                                    <input class="form-control" type="text" name="firstname" required
                                        value="{{ $user->firstname }}">

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label class="form-control-label">@lang('Last Name')</label>

                                    <input class="form-control" type="text" name="lastname" required
                                        value="{{ $user->lastname }}">

                                </div>

                            </div>

                        </div>



                        <div class="row">

                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>@lang('Email') </label>

                                    <input class="form-control" type="email" name="email"
                                        value="{{ $user->email }}" required>

                                    <a style="padding: 6px 3px;"
                                        href='{{ route('admin.users.send.verify.code', ['type' => 'email', 'id' => $user->id]) }}'>@lang('Resend verification code')</a>

                                </div>

                            </div>



                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>@lang('Mobile Number') </label>

                                    <div class="input-group ">

                                        <span class="input-group-text mobile-code"></span>

                                        <input type="number" name="mobile" value="{{ old('mobile') }}" id="mobile"
                                            class="form-control checkUser" required>

                                    </div>

                                    <a style="padding: 6px 3px;"
                                        href='{{ route('admin.users.send.verify.code', ['type' => 'sms', 'id' => $user->id]) }}'>@lang('Resend verification code')</a>



                                </div>

                            </div>

                        </div>





                        <div class="row mt-4">

                            <div class="col-md-12">

                                <div class="form-group ">

                                    <label>@lang('Address')</label>

                                    <input class="form-control" type="text" name="address"
                                        value="{{ @$user->address->address }}">

                                </div>

                            </div>



                            <div class="col-xl-3 col-md-6">

                                <div class="form-group">

                                    <label>@lang('City')</label>

                                    <input class="form-control" type="text" name="city"
                                        value="{{ @$user->address->city }}">

                                </div>

                            </div>



                            <div class="col-xl-3 col-md-6">

                                <div class="form-group ">

                                    <label>@lang('State')</label>

                                    <input class="form-control" type="text" name="state"
                                        value="{{ @$user->address->state }}">

                                </div>

                            </div>



                            <div class="col-xl-3 col-md-6">

                                <div class="form-group ">

                                    <label>@lang('Zip/Postal')</label>

                                    <input class="form-control" type="text" name="zip"
                                        value="{{ @$user->address->zip }}">

                                </div>

                            </div>



                            <div class="col-xl-3 col-md-6">

                                <div class="form-group ">

                                    <label>@lang('Country')</label>

                                    <select name="country" class="form-control">

                                        @foreach ($countries as $key => $country)
                                            <option data-mobile_code="{{ $country->dial_code }}"
                                                data-image='{{ getImage(getFilePath('countries') . '/' . strtolower($key) . '.png') }}'
                                                value="{{ $key }}">{{ __($country->country) }}</option>
                                        @endforeach

                                    </select>



                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label>@lang('Email Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')"
                                        data-off="@lang('Unverified')" name="ev"
                                        @if ($user->ev) checked @endif>
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label>@lang('Mobile Verification')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Verified')"
                                        data-off="@lang('Unverified')" name="sv"
                                        @if ($user->sv) checked @endif>
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label>@lang('Latest news')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success"
                                        data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Yes')"
                                        data-off="@lang('no')" name="latest_news"
                                        @if ($user->latest_news) checked @endif>
                                </div>
                            </div>


                            <div class="form-group">

                                <label>@lang('Note')</label>

                                <textarea class="form-control" name="note" rows="4" required></textarea>

                            </div>
                            <div class="form-group"
                                style=" display: flex; justify-content: space-between; align-items: center;">

                                <p class='last-update'>
                                    {{ __('Last Update : ') . date_format($user->updated_at, 'd-m-Y h:i a') }}</p>

                                <a href="javascript:void(0)" data-notes="{{ $user->notes }}"
                                    class=" notes btn btn-outline--primary box--shadow1">@lang('View History')</a>

                            </div>
                        </div>





                        <div class="row mt-4">

                            <div class="col-md-12">

                                <div class="form-group">

                                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')

                                    </button>

                                </div>

                            </div>



                        </div>

                    </form>

                </div>

            </div>



        </div>

    </div>







    {{-- Add Sub Balance MODAL --}}

    <div id="addSubModal" class="modal fade" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Balance')</span></h5>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <i class="las la-times"></i>

                    </button>

                </div>

                <form action="{{ route('admin.users.add.balance', $user->id) }}" method="POST">

                    @csrf

                    <input type="hidden" name="act">

                    <div class="modal-body">

                        <div class="form-group">

                            <label>@lang('Amount')</label>

                            <div class="input-group">

                                <input type="number" step="any" name="amount" class="form-control"
                                    placeholder="@lang('Please provide positive amount')" required>

                                <div class="input-group-text">{{ __($general->cur_text) }}</div>

                            </div>

                        </div>

                        <div class="form-group">

                            <label>@lang('Remark')</label>

                            <textarea class="form-control" placeholder="@lang('Remark')" name="note" rows="4" required></textarea>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>

                    </div>

                </form>

            </div>

        </div>

    </div>




    {{-- User Status MODAL --}}
    <div id="userStatusModal" class="modal fade" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        @if ($user->status == 1)
                            <span>@lang('Ban User')</span>
                        @else
                            <span>@lang('Unban User')</span>
                        @endif

                    </h5>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <i class="las la-times"></i>

                    </button>

                </div>

                <form action="{{ route('admin.users.status', $user->id) }}" method="POST">

                    @csrf

                    <div class="modal-body">

                        @if ($user->status == 1)
                            <h6 class="mb-2">@lang('If you ban this user he/she won\'t able to access his/her dashboard.')</h6>

                            <div class="form-group">

                                <label>@lang('Reason')</label>

                                <textarea class="form-control" name="reason" rows="4" required></textarea>

                            </div>

                            <div class="form-group">

                                <label>@lang('Note (admin note)')</label>

                                <textarea class="form-control" name="ban_note" rows="4" required></textarea>

                            </div>
                        @else
                            <p><span>@lang('Ban reason was'):</span></p>

                            <p>{{ $user->ban_reason }}</p>
                            <hr>
                            <p><span>@lang('Ban admin note was'):</span></p>

                            <p>{{ $user->ban_note }}</p>
                            <h4 class="text-center mt-3">@lang('Are you sure to unban this user?')</h4>
                        @endif

                    </div>

                    <div class="modal-footer">

                        @if ($user->status == 1)
                            <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                        @else
                            <button type="button" class="btn btn--dark"
                                data-bs-dismiss="modal">@lang('No')</button>

                            <button type="submit" class="btn btn--primary">@lang('Yes')</button>
                        @endif

                    </div>

                </form>

            </div>

        </div>

    </div>

    {{-- Reset Pasword MODAL --}}

    <div id="resetPasswordModal" class="modal fade" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title"><span class="type"></span> <span>@lang('Reset Password')</span></h5>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <i class="las la-times"></i>

                    </button>

                </div>

                <form action="{{ route('admin.users.reset.password', $user->id) }}" method="POST">

                    @csrf

                    <input type="hidden" name="act">

                    <div class="modal-body">

                        <div class="form-group">

                            <label class="form-label">@lang('Password')</label>

                            <input type="password" class="form-control form--control" name="password" required>
                            @if ($general->secure_password)
                                <div class="input-popup">
                                    <p class="error lower">@lang('1 small letter minimum')</p>
                                    <p class="error capital">@lang('1 capital letter minimum')</p>
                                    <p class="error number">@lang('1 number minimum')</p>
                                    <p class="error special">@lang('1 special character minimum')</p>
                                    <p class="error minimum">@lang('6 character password')</p>
                                </div>
                            @endif
                        </div>
                        <div class="form-group">

                            <label class="form-label">@lang('Confirm Password')</label>
                            <input type="password" class="form-control form--control" name="password_confirmation"
                                required>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection





@push('script')
    <script>
        (function($) {

            "use strict"

            $('.bal-btn').click(function() {

                var act = $(this).data('act');

                $('#addSubModal').find('input[name=act]').val(act);

                if (act == 'add') {

                    $('.type').text('Add');

                } else {

                    $('.type').text('Subtract');

                }

            });

            let mobileElement = $('.mobile-code');

            $('select[name=country]').change(function() {

                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);

            });



            $('select[name=country]').val('{{ @$user->country_code }}');

            let dialCode = $('select[name=country] :selected').data('mobile_code');

            let mobileNumber = `{{ $user->mobile }}`;

            mobileNumber = mobileNumber.replace(dialCode, '');

            $('input[name=mobile]').val(mobileNumber);

            mobileElement.text(`+${dialCode}`);




        })(jQuery);



        $(document).ready(function() {

            $('.select2').select2({

                templateResult: formatCountry,

                templateSelection: formatCountry

            });

        });

        $(document).ready(function() {

            $('select[name="country"]').select2({

                templateResult: formatOption // Custom function to format options with images

            });

        });



        function formatOption(option) {

            if (!option.id) {

                return option.text;

            }



            var image = $(option.element).data('image');

            var mobileCode = $(option.element).data('mobile_code');

            var $option = $('<span><img style="max-width: 10%;" src="' + image + '" class="img-flag" /> ' + option.text +
                '</span>');

            return $option;



        };


        var notes = {!! json_encode($user->notes) !!};

        var notesHtml = '';



        for (var i = 0; i < notes.length; i++) {

            var note = notes[i].note;

            var createdAt = new Date(notes[i].created_at);



            // Format the createdAt date

            var formattedDate = createdAt.toLocaleDateString('en-US', {

                month: '2-digit',

                day: '2-digit',

                year: 'numeric'

            });

            var formattedTime = createdAt.toLocaleTimeString('en-US', {

                hour: '2-digit',

                minute: '2-digit',

                hour12: true

            });



            // Append each note and formatted date and time to the notesHtml string

            notesHtml += '<div>';

            notesHtml += '<p>Note: ' + note + '</p>';

            notesHtml += '<p>At: ' + formattedDate + ' ' + formattedTime + '</p>';

            notesHtml += '</div>';

            notesHtml += '<hr>';
        }

        $('.notes-box').html(notesHtml);
    </script>
@endpush
