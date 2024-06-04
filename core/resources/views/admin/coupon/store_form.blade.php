@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">@lang('Information of store')</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.store.save', $store ? $store->id : '') }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row justify-content-center mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview"
                                                     style="background-image: url({{ getImage(getFilePath('store') . '/' . @$store->image) }})">
                                                    <button type="button" class="remove-image"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image"
                                                       id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1"
                                                       class="bg--success">@lang('Coupon Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'):
                                                    <b>@lang('jpeg'),
                                                        @lang('jpg'), @lang('png').</b> @lang('Image will be resized into
                                                                                                            200x200px')
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-6">

                                <div class="form-group">

                                    <label>@lang('Name')</label>

                                    <input class="form-control" value="{{ @$store->name }}" type="text" name="name"
                                           required>

                                </div>
                            </div>


                            <div class="col-md-6">


                                <div class="form-group">
                                    <label class="form-control-label">@lang('Category')</label>
                                    <select name="category_id" class="form-control" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ @$store->category_id == $category->id ? 'selected' : '' }}>
                                                {{ __($category->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Cashback')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="any" name="cashback"
                                               value="{{ $store ? $store->getRawOriginal('cashback') : old('cashback') }}"
                                               required>
                                        <select name='cashbacktype_id' style='width=65px;' class="input-group-text">

                                            @foreach ($cashbacktypes as $cashbacktype)
                                                <option value="{{ $cashbacktype->id }}"
                                                    {{ isset($store->cashback_type) && $store->cashback_type == $cashbacktype->id ? 'selected' : '' }}>
                                                    {{ __($cashbacktype->name) }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('user %')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="any" name="user_percentage"
                                               value="{{ $store ? $store->getRawOriginal('user_percentage') : old('user_percentage') }}"
                                               required>
                                        <span style='width=65px;' class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">


                                <div class="form-group">

                                    <div>

                                        <label style="width: 49%; padding-left: 10px;">@lang('Cashback Offer')</label>

                                        <label style="width: 49%; padding-left: 10px;"> @lang('Ending Date') </label>

                                    </div>

                                    <div class="input-group">

                                        <input class="form-control" value="{{ @$store->offer_cashback }}" type="number"
                                               step="any" name="offer_cashback">

                                        <input class="form-control" type="date" name="ending_date"
                                               value="{{ isset($store->ending_date) ? (new DateTime($store->ending_date))->format('d-m-Y h:i a') : old('ending_date') }}"
                                               autocomplete="off">
                                    </div>


                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Store Url')</label>
                                    <input class="form-control" type="text" name="url"
                                           value="{{ $store ? $store->url : old('url') }}">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">

                                    <label class="form-control-label">@lang('Store Network')</label>

                                    <select name="network_id" class="form-control" required>



                                        @foreach ($networks as $network)
                                            <option
                                                value="{{ $network->id }}" {{ isset($store->network) && $store->network_id == $network->id ? 'selected' : '' }}>

                                                {{ __($network->name) }}   </option>
                                        @endforeach

                                    </select>


                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>@lang('Marketing channels')</label>

                                    <div style='border: solid 0.3px #cccccc; padding: 8px;'>

                                        <div class="form-group">

                                            <div>

                                                <label
                                                    style="width: 24%; padding-left: 10px;">{{ __('social media') }}</label>

                                                <label style="width: 24%; padding-left: 10px;">{{ __('Email') }}</label>

                                                <label
                                                    style="width: 24%; padding-left: 10px;">{{ __('Cashback') }}</label>

                                                <label
                                                    style="width: 24%; padding-left: 10px;">{{ __('Coupon') }}</label>

                                            </div>

                                            <input type="checkbox"
                                                   {{ isset($store->marketing_channels) && json_decode($store->marketing_channels)->social == 1 ? 'checked' : '' }}

                                                   data-width="24%" data-onstyle="-success" data-offstyle="-danger"
                                                   data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                                   name="social">


                                            <input type="checkbox"
                                                   {{ isset($store->marketing_channels) && json_decode($store->marketing_channels)->email == 1 ? 'checked' : '' }}
                                                   data-width="24%" data-onstyle="-success" data-offstyle="-danger"
                                                   data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                                   name="email">


                                            <input type="checkbox"
                                                   {{ isset($store->marketing_channels) && json_decode($store->marketing_channels)->cash == 1 ? 'checked' : '' }}
                                                   data-width="24%" data-onstyle="-success" data-offstyle="-danger"
                                                   data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                                   name="cash">


                                            <input type="checkbox"
                                                   {{ isset($store->marketing_channels) && json_decode($store->marketing_channels)->coupon == 1 ? 'checked' : '' }}
                                                   data-width="24%" data-onstyle="-success" data-offstyle="-danger"
                                                   data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                                   name="coupon">

                                        </div>

                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control" rows="4"
                                              required>{{ $store ? $store->description : old('description') }}</textarea>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="form-group">
                                    <label>@lang('Terms & Conditions')</label>
                                    <textarea name="terms" class="form-control" rows="4"
                                              required>{{ $store ? $store->terms : old('terms') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Countries')</label>
                                    <div class="scrollbox">
                                        @foreach ($countries as $key => $country)
                                            <div class="{{ $key % 2 == 0 ? 'even' : 'odd' }}">
                                                <input name="countries_id[]" value="{{ $country->id }}" type="checkbox"
                                                    {{ isset($store->countries) && in_array($country->id, $store->countries->pluck('id')->toArray()) ? 'checked' : '' }}>{{ $country->country_name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Accepted Withdrawal Methods')</label>
                                    <div class="scrollbox">
                                        @foreach ($withdrawMethods as $key => $method)
                                            <div class="{{ $key % 2 == 0 ? 'even' : 'odd' }}">
                                                <input name="withdrawlmethod_id[]" value="{{ $method->id }}" type="checkbox"
                                                    {{ (isset($store->withdrawMethods) && in_array($method->id, $store->withdrawMethods->pluck('withdraw_method_id')->toArray())) ? 'checked' : '' }}
                                                >
                                                {{ $method->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Channels')</label>
                                    <div class="scrollbox">
                                        @foreach ($channels as $key => $channel)
                                            <div class="{{ $key % 2 == 0 ? 'even' : 'odd' }}">
                                                <input name="channels_id[]" value="{{ $channel->id }}" type="checkbox"
                                                    {{ isset($store->channels) && in_array($channel->id, $store->channels->pluck('id')->toArray()) ? 'checked' : '' }}>{{ $channel->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="form-group">

                                    <label>@lang('Featured')</label>

                                    <input type="checkbox"
                                           {{ @$store->featured == 1 ? 'checked' : '' }} data-width="100%"
                                           data-onstyle="-success" data-offstyle="-danger"
                                           data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                           name="featured">

                                </div>
                            </div>
x

                            @if($store)

                                <div class="col-md-6">

                                    <div class="form-group statusGroup">

                                        <label>@lang('Status')</label>

                                        <input type="checkbox"
                                               {{ $store->status == 1 ? 'checked' : '' }} data-width="100%"
                                               data-onstyle="-success" data-offstyle="-danger"
                                               data-bs-toggle="toggle" data-on="@lang('Active')"
                                               data-off="@lang('Inactive')" name="status">

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group statusGroup">

                                        <label>@lang('Edit note')</label>

                                        <textarea class="form-control" name="note" rows="4" required></textarea>

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group statusGroup"
                                         style=" display: flex; justify-content: space-between; align-items: center;">

                                        <p class='last-update'>{{ __('Last Update : ') }} {{ date_format($store->updated_at, 'd-m-Y h:i a') }}</p>

                                        <a href="javascript:void(0)"
                                           class=" notes btn btn-outline--primary box--shadow1">@lang('View History')</a>

                                    </div>
                                </div>
                            @endif
                        </div>


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

@endsection


@push('breadcrumb-plugins')
    @if (@$coupon->user_id)
        <button class="btn btn-sm btn-outline--primary actionBtn">
            <i class="las la-list"></i>@lang('Action')
        </button>
    @endif

    <a href="{{ route('admin.store.all') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-undo"></i> @lang('Back')
    </a>
@endpush

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush

@push('style')
    <style>
        .select2-container {
            z-index: 9999;
        }
    </style>
@endpush
@push('script')
    <script>


        (function ($) {
            "use strict";

            var notes = {!! @$store->notes !!}; // Assuming $store->note is a JSON string

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


            $('.notes-box').html(notesHtml);
            var start = new Date(),
                prevDay,
                startHours = 0;

            // 09:00 AM
            start.setHours(0);
            start.setMinutes(0);

            // If today is Saturday or Sunday set 10:00 AM
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
                onSelect: function (fd, d, picker) {
                    // Do nothing if selection was cleared
                    if (!d) return;

                    var day = d.getDay();

                    // Trigger only if date is changed
                    if (prevDay != undefined && prevDay == day) return;
                    prevDay = day;

                    // If chosen day is Saturday or Sunday when set
                    // hour value for weekends, else restore defaults
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

            $('.store-list').select2({
                ajax: {
                    url: "{{ route('admin.store.list') }}",
                    type: "get",
                    dataType: 'json',
                    delay: 1000,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page,
                            rows: 5,
                        };
                    },
                    processResults: function (response, params) {
                        params.page = params.page || 1;
                        return {
                            results: response,
                            pagination: {
                                more: params.page < response.length
                            }
                        };
                    },
                    cache: false
                },
                dropdownParent: $('.card-body')
            });

            $('[name=action]').on('change', function () {
                var action = $('[name=action]').val();
                if (action == 3) {
                    $('.reason').show();
                } else {
                    $('.reason').hide();
                }
            }).change();

            $(document).on('click', '.actionBtn', function () {
                var modal = $('#actionModal');
                let data = $(this).data();
                modal.modal('show');
            });

        })(jQuery);
    </script>
@endpush
