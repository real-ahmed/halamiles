@extends('admin.layouts.app')

@section('panel')
    <div class="ha"></div>
    <div class="row">

        <div class="col-lg-12">

            <div class="card b-radius--10 ">

                <div class="card-body p-0">

                    <div class="table-responsive--md  table-responsive">

                        <table class="table table--light style--two">

                            <thead>

                                <tr>

                                    <th>@lang('User')</th>

                                    <th>@lang('Email')</th>

                                    <th>@lang('Phone')</th>

                                    <th>@lang('Country')</th>

                                    <th>@lang('Verifications')</th>

                                    <th>@lang('Joined At')</th>

                                    <th>@lang('Action')</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($users as $user)
                                    <tr>

                                        <td data-label="@lang('User')">

                                            <span class="fw-bold">{{ $user->fullname }}</span>

                                            <br>

                                            <span class="small">

                                                <a
                                                    href="{{ route('admin.users.detail', $user->id) }}"><span>@</span>{{ $user->username }}</a>

                                            </span>

                                        </td>





                                        <td data-label="@lang('Email')">

                                            {{ $user->email }}

                                        </td>



                                        <td data-label="@lang('Phone')">

                                            {{ $user->mobile }}

                                        </td>

                                        <td data-label="@lang('Country')">

                                            <span class="fw-bold" title="{{ @$user->address->country }}"><img
                                                    style="max-width: 4rem;"
                                                    src='{{ getImage(getFilePath('countries') . '/' . strtolower($user->country_code) . '.png') }}'></span>

                                        </td>



                                        <td data-label="@lang('Verifications')">

                                            @if ($user->ev == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('email')</span>
                                            @endif

                                            @if ($user->sv == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('phone')</span>
                                            @endif

                                        </td>



                                        <td data-label="@lang('Joined At')">

                                            {{ showDateTime($user->created_at) }} <br>
                                            {{ diffForHumans($user->created_at) }}

                                        </td>



                                        <td data-label="@lang('Action')">

                                            <a href="{{ route('admin.users.detail', $user->id) }}"
                                                class="btn btn-sm btn-outline--primary">

                                                <i class="las la-desktop text--shadow"></i> @lang('Details')

                                            </a>

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
                <div class="card-footer py-4">
                    <a href="{{ route('admin.download.csv', ['table' => 'users', 'columns' => 'id,firstname,lastname,username,email,country_code,mobile,address,created_at']) }}"
                        class="btn btn-outline--primary box--shadow1">Download CSV</a>
                    @if ($users->hasPages())
                        {{ paginateLinks($users) }}

                </div>
                @endif

            </div>

        </div>





    </div>




    <div id="userModal" class="modal fade" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title"></h5>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <form method="POST" action="{{ route('admin.users.create')}}" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-body">


                        <div class="form-group">

                            <label class="form-label">@lang('Username')</label>

                            <input type="usename" class="form-control form--control checkUser" name="username"
                                value="{{ old('username') }}" required>

                            <small class="text-danger usernameExist"></small>

                        </div>

                        <div class="form-group">

                            <label class="form-label">@lang('E-Mail Address')</label>

                            <input type="email" class="form-control form--control checkUser" name="email"
                                value="{{ old('email') }}" required>

                        </div>


                        <div class="form-group ">

                            <label>@lang('Country')</label>

                            <select name="country" class="form-control">

                                @foreach ($countries as $key => $country)
                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}">
                                        {{ __($country->country) }}</option>
                                @endforeach

                            </select>



                        </div>

                        <div class="form-group">

                            <label class="form-label">@lang('Mobile')</label>

                            <div class="input-group ">

                                <span class="input-group-text mobile-code"></span>

                                <input type="hidden" name="mobile_code">

                                <input type="hidden" name="country_code">

                                <input type="number" name="mobile" value="{{ old('mobile') }}"
                                    class="form-control form--control checkUser" required>

                            </div>

                            <small class="text-danger mobileExist"></small>

                        </div>

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
                            <input type="password" class="form-control form--control" name="password_confirmation" required>
                        </div>

                        <div class="form-group ">

                            <label>@lang('First Name')</label>

                            <input class="form-control" type="text" name="firstname" required
                                >

                        </div>


                        <div class="form-group">

                            <label class="form-control-label">@lang('Last Name')</label>

                            <input class="form-control" type="text" name="lastname" required
                                >

                        </div>
                        <div class="form-group ">
                            <label>@lang('Address')</label>
                            <input class="form-control" type="text" name="address"
                                value="{{ @$user->address->address }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('City')</label>
                            <input class="form-control" type="text" name="city"
                                value="{{ @$user->address->city }}">
                        </div>
                        <div class="form-group ">
                            <label>@lang('State')</label>
                            <input class="form-control" type="text" name="state"
                                value="{{ @$user->address->state }}">
                        </div>
                        <div class="form-group ">
                            <label>@lang('Zip/Postal')</label>
                            <input class="form-control" type="text" name="zip"
                                value="{{ @$user->address->zip }}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Email Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                name="ev">
                        </div>
                        <div class="form-group">
                            <label>@lang('Mobile Verification')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')"
                                name="sv">
                        </div>


                        <div class="form-group">
                            <label>@lang('Latest news')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                name="latest_news">
                        </div>

                        <div class="form-group">
                            <label>@lang('Sign up gift')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                name="gift">
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
    <div class="sort">

        <form action="" method="GET" id="sortForm">

            <input type="hidden" name="search" value="{{ request()->query('search') }}">

            <select name="sort_by" id="column" onchange="document.getElementById('sortForm').submit()">

                <option value="created_at" @if (request()->input('sort_by') == 'join at') selected @endif>Created at</option>

                <option value="username" @if (request()->input('sort_by') == 'username') selected @endif>Name</option>

                <option value="email" @if (request()->input('sort_by') == 'email') selected @endif>Email</option>

            </select>

            <select name="sort_direction" id="order" onchange="document.getElementById('sortForm').submit()">

                <option value="desc" @if (request()->input('sort_direction') == 'desc') selected @endif>Descending</option>

                <option value="asc" @if (request()->input('sort_direction') == 'asc') selected @endif>Ascending</option>

            </select>

        </form>

    </div>

    <div class="d-flex flex-wrap justify-content-sm-end header-search-wrapper">

        <form action="" method="GET" class="header-search-form">

            <div class="input-group has_append">

                <input type="text" name="search" class="form-control bg-white text--black"
                    placeholder="@lang('Search Username')" value="{{ request()->search }}">

                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>

                <input type="hidden" name="sort_by"
                    value="{{ request()->query('sort_by') ? request()->query('sort_by') : 'created_at' }}">

                <input type="hidden" name="sort_direction"
                    value="{{ request()->query('sort_direction') ? request()->query('sort_direction') : 'desc' }}">

            </div>

        </form>

        <button class="btn btn-outline--primary box--shadow1 addUser"><i
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



        @media (max-width:400px) {

            .header-search-form {

                width: 100%
            }

        }

        .right-part {

            display: flex;

            flex-direction: row;

            align-items: center;

            justify-content: flex-end;

        }

        #sortForm {

            display: flex;

            flex-direction: row;

            align-content: center;

            align-items: center;

        }

        #sort_by,

        #sort_direction {
            font-size: 17px;
            margin-right: 20px;
        }
    </style>
@endpush


@push('script')
    <script>
        (function($) {





            "use strict";

            var modal = $('#userModal');




            $('.addUser').click(function() {

                modal.find('.modal-title').text("@lang('Add User')");

                modal.find('.statusGroup').hide();



                modal.find('textarea[name="note"]').removeAttr('required');

                modal.modal('show');



            });



            modal.on('shown.bs.modal', function(e) {

                $(document).off('focusin.modal');

            });



            modal.on('hidden.bs.modal', function() {

                modal.find('form')[0].reset();

                modal.find('.profilePicPreview').css('background-image',
                    'url({{ getImage(getFilePath('store')) }})');

                modal.find('.profilePicUpload').val('');

                modal.find('input[type=checkbox]').prop('checked', false);

                modal.find('.last-update').text(originalText);
            });



            let mobileElement = $('.mobile-code');

            $('select[name=country]').change(function() {

                mobileElement.text(`+${$('select[name=country] :selected').data('mobile_code')}`);

            });



            $('select[name=country]').val('{{ @$user->country_code }}');

            let dialCode = $('select[name=country] :selected').data('mobile_code');

            let mobileNumber = '{{ @$user->mobile }}';

            mobileNumber = mobileNumber.replace(dialCode, '');

            $('input[name=mobile]').val(mobileNumber);

            mobileElement.text(`+${dialCode}`);



        })(jQuery);
    </script>
@endpush
