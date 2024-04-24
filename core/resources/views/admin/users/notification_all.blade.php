@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <form action="" class="notify-form">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Add a selection for sending type -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Send To') </label>
                                    <select name="recipient_type" class="form-control">
                                        <option value="all">@lang('All Users')</option>
                                        <option value="selected">@lang('Selected Users')</option>
                                        <option value="latest_news">@lang('Users With Latest News')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12" style="display:none;" id="userSelectionDiv">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Select Users')</label>
                                    <input type="text" id="userSearch" placeholder="@lang('Search Users')"
                                        class="form-control mb-2">
                                    <div class="user-list scrollbox">
                                        @foreach ($users as $key => $user)
                                            <div class="user-item {{ $key % 2 == 0 ? 'even' : 'odd' }}"
                                                data-name="{{ $user->email }}">
                                                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}"
                                                    id="user_{{ $user->id }}">
                                                <label for="user_{{ $user->id }}">{{ $user->email }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Subject') </label>
                                    <input type="text" class="form-control" placeholder="@lang('Email subject')"
                                        name="subject" required />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="fw-bold">@lang('Message') </label>
                                    <textarea name="message" rows="10" class="form-control nicEdit"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn w-100 h-45 btn--primary mr-2">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="modal fade" data-bs-backdrop="static" id="notificationSending">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Notification Sending')</h5>
                </div>
                <div class="modal-body">
                    <h4 class="text--danger text-center">@lang('Don\'t close or refresh the window till finish')</h4>
                    <div class="mail-wrapper">
                        <div class="mail-icon world-icon"><i class="las la-globe"></i></div>
                        <div class='mailsent'>
                            <div class='envelope'>
                                <i class='line line1'></i>
                                <i class='line line2'></i>
                                <i class='line line3'></i>
                                <i class="icon fa fa-envelope"></i>
                            </div>
                        </div>
                        <div class="mail-icon mail-icon"><i class="las la-envelope-open-text"></i></div>
                    </div>
                    <div class="mt-3">
                        <div class="progress">
                            <div class="progress-bar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <span class="text--primary">@lang('Notification will send via ') @if ($general->en)
            <span class="badge badge--warning">@lang('Email')</span>
            @endif @if ($general->sn)
                <span class="badge badge--warning">@lang('SMS')</span>
            @endif
    </span>
@endpush

@push('style')
    <style>
        /* Optional: Style the select element and input */
        #userSelect {
            overflow-y: auto;
            max-height: 150px;
        }
    </style>
@endpush
@push('script')
    <script>
        (function($) {
            "use strict";
            $('select[name="recipient_type"]').change(function() {
                $('#userSelectionDiv').toggle($(this).val() === 'selected');
            });
            $('select[name="recipient_type"]').change(function() {
                $('#userSelectionDiv').toggle($(this).val() === 'selected');
            });

            // User filtering logic
            $('#userSearch').on('input', function() {
                const query = $(this).val().toLowerCase();
                $(".user-item").each(function() {
                    $(this).toggle($(this).data('name').toLowerCase().includes(query));
                });
            });





            $('.notify-form').on('submit', function(e) {
                e.preventDefault();
                const recipientType = $('select[name="recipient_type"]').val();
                $('.progress-bar').css('width', `0%`);
                $('.progress-bar').text(`0%`);
                $('.sent').text(0);
                $('#notificationSending').modal('show');

                if (recipientType === 'all') {
                    postMail($(this), 0);
                } else if (recipientType === 'selected') {
                    postMailToSelected($(this));
                } else if (recipientType === 'latest_news') {
                    postMailToLatestNews($(this));
                }
            });



            function postMail(form, skip) {
                var _token = form.find('[name=_token]').val();
                var subject = form.find('[name=subject]').val();
                var message = form.find('.nicEdit-main').html();

                $.post("{{ route('admin.users.notification.all.send') }}", {
                    "subject": subject,
                    "_token": _token,
                    "skip": skip,
                    "message": message,
                    "recipient_type": 'all'
                }, function(response) {
                    handleResponse(response, form, {{ $usersCount }}, postMail);
                });
            }

            function postMailToSelected(form, skip) {
                var _token = form.find('[name=_token]').val();
                var subject = form.find('[name=subject]').val();
                var message = form.find('.nicEdit-main').html();
                var selectedUsers = [];
                $('input[name="selected_users[]"]:checked').each(function() {
                    selectedUsers.push($(this).val());
                });

                $.post("{{ route('admin.users.notification.selected.send') }}", {
                    "subject": subject,
                    "_token": _token,
                    "message": message,
                    "recipient_type": 'selected',
                    "selected_users": selectedUsers
                }, function(response) {
                    handleResponse(response, form, selectedUsers.length, postMailToSelected);
                });
            }

            function postMailToLatestNews(form, skip) {
                var _token = form.find('[name=_token]').val();
                var subject = form.find('[name=subject]').val();
                var message = form.find('.nicEdit-main').html();

                $.post("{{ route('admin.users.notification.latest-news.send') }}", {
                    "subject": subject,
                    "_token": _token,
                    "message": message,
                    "recipient_type": 'latest_news'
                }, function(response) {
                    handleResponse(response, form, {{ $usersNewsCount }}, postMailToLatestNews);
                });
            }

            function handleResponse(response, form, usersCount, func) {
                var rest = usersCount - response.total_sent;
                var sentPercent = response.total_sent / usersCount * 100;
                if (sentPercent > 100) {
                    sentPercent = 100;
                }
                sentPercent = sentPercent.toFixed(0)
                $('.progress-bar').css('width', `${sentPercent}%`);
                $('.progress-bar').text(`${sentPercent}%`);
                $('.sent').text(response.total_sent);
                if (rest == 0) {
                    setTimeout(() => {
                        $('#notificationSending').modal('hide');
                        form.find('[name=subject]').val('');
                        form.find('.nicEdit-main').html('<span></span>');
                        notify('success', 'Mail sent to all users successfully')
                    }, 3000);
                    return false;
                }
                func(form, response.total_sent);
            }



        })(jQuery);
    </script>
@endpush
