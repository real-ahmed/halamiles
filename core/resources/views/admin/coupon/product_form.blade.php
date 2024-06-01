@extends('admin.layouts.app')
@section('panel')
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">@lang('Information of product')</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.product.save', $product ? $product->id : '') }}" method="POST"
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
                                                    style="background-image: url({{ getImage(getFilePath('product') . '/' . @$product->image, getFileSize('product')) }})">
                                                    <button type="button" class="remove-image"><i
                                                            class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input type="file" class="profilePicUpload" name="image"
                                                    id="profilePicUpload1" accept=".png, .jpg, .jpeg">
                                                <label for="profilePicUpload1" class="bg--success">@lang('product Image')</label>
                                                <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'),
                                                        @lang('jpg'), @lang('png').</b> @lang('Image will be resized into
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    300x200px') </small>
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
                                    <label>@lang('Title')</label>
                                    <input class="form-control" type="text" name="title"
                                        value="{{ $product ? $product->title : old('title') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Category')</label>
                                    <select name="category_id" class="form-control" required>
                                        <option hidden>@lang('Select One')</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ @$product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ __($category->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Url')</label>
                                    <input class="form-control" type="text" name="url"
                                        value="{{ $product ? $product->url : old('url') }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Ending Date')</label>
                                    <input class="form-control" type="text" name="ending_date"
                                        value="{{ $product ? date_format($product->ending_date, 'd-m-Y h:i a') : old('ending_date') }}"
                                        autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Store')</label>
                                    <select name="store_id" class="form-control store-list select2-basic" required>
                                        <option value="" hidden>@lang('Select One')</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}"
                                                {{ $product ? ($store->id == $product->store_id ? 'selected' : '') : '' }}>
                                                {{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Status')</label>
                                    @if ($product)
                                        <input type="checkbox" data-width="100%" data-onstyle="-success"
                                            data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')"
                                            data-off="@lang('Inactive')" name="status"
                                            @if (@$product->status == 1) checked @endif>
                                    @else
                                        <input type="checkbox" data-width="100%" data-onstyle="-success"
                                            data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')"
                                            checked disabled>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Cashback')</label>
                                    <div class="input-group">
                                        <input class="form-control" type="number" step="any" name="cashback"
                                            value="{{ $product ? $product->cashback : old('cashback') }}" required>
                                        <select name='cashbacktype_id' style='width=65px;' class="input-group-text">

                                            @foreach ($cashbacktypes as $cashbacktype)
                                                <option value="{{ $cashbacktype->id }}"
                                                    {{ isset($product->cashback_type) && $product->cashback_type == $cashbacktype->id ? 'selected' : '' }}>
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

                        </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Trend')</label>
                                    <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                        data-bs-toggle="toggle" data-on="@lang('Yes')" data-off="@lang('No')"
                                        name="trend" @if (@$product->trend) checked @endif>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Countries')</label>
                                    <div class="scrollbox">
                                        @foreach ($countries as $key => $country)
                                            <div class="{{ $key % 2 == 0 ? 'even' : 'odd' }}">
                                                <input name="countries_id[]" value="{{ $country->id }}" type="checkbox"
                                                    {{ isset($product->countries) && in_array($country->id, $product->countries->pluck('id')->toArray()) ? 'checked' : '' }}>{{ $country->country_name }}
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
                                                    {{ isset($product->channels) && in_array($channel->id, $product->channels->pluck('id')->toArray()) ? 'checked' : '' }}>{{ $channel->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label>@lang('Description')</label>
                            <textarea id="description" maxlength="35" name="description" class="form-control" rows="4" required>{{ $product ? $product->description : old('description') }}</textarea>
                            <small id="descriptionCounter" class="form-text">
                                {{ strlen($product ? $product->description : old('description')) }}/35
                            </small>
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

    <div id="actionModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.product.status') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Select product Status')</label>
                            <select name="action" class="form-control" required>
                                <option value="1" {{ @$product->status == 1 ? 'selected' : '' }}>@lang('Approve')
                                </option>
                                <option value="3" {{ @$product->status == 3 ? 'selected' : '' }}>@lang('Reject')
                                </option>
                            </select>
                        </div>
                        <div class="form-group reason">
                            <label>@lang('Reason')</label>
                            <textarea name="reason" class="form-control" rows="4">{{ @$product->reason }}</textarea>
                        </div>
                        <input type="hidden" name="product_id" value="{{ @$product->id }}">
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
    @if (@$product->user_id)
        <button class="btn btn-sm btn-outline--primary actionBtn">
            <i class="las la-list"></i>@lang('Action')
        </button>
    @endif

    <a href="{{ route('admin.product.all') }}" class="btn btn-sm btn-outline--primary">
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
        (function($) {
            "use strict";
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
                onSelect: function(fd, d, picker) {
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
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page,
                            rows: 5,
                        };
                    },
                    processResults: function(response, params) {
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

            $('[name=action]').on('change', function() {
                var action = $('[name=action]').val();
                if (action == 3) {
                    $('.reason').show();
                } else {
                    $('.reason').hide();
                }
            }).change();

            $(document).on('click', '.actionBtn', function() {
                var modal = $('#actionModal');
                let data = $(this).data();
                modal.modal('show');
            });

        })(jQuery);

        $(document).ready(function() {
            $('#description').on('input', function() {
                var length = $(this).val().length;
                var maxLength = $(this).attr('maxlength');
                $('#descriptionCounter').text(length + '/' + maxLength);
            });
        });
    </script>
@endpush
