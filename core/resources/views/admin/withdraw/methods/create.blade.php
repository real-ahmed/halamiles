@extends('admin.layouts.app')

@section('panel')

    <div class="row">

        <div class="col-lg-12">

            <div class="card mb-4">

                <form action="{{ route('admin.withdraw-method.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="card-body">

                        <div class="payment-method-item">



                            <div class="payment-method-body">

                                <div class="row mb-none-15">

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-15">

                                        <div class="form-group">

                                            <label>@lang('Gateway Name')</label>

                                            <input type="text" class="form-control " name="name" value="{{ old('name') }}" required/>

                                        </div>


                                        <div class="form-group">

                                            <label>@lang('Currency Symbol')</label>

                                            <input type="text" class="form-control "  name="symbol" value="{{ old('symbol') }}" required/>

                                        </div>

                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-15">



                                        <div class="form-group">

                                            <label>@lang('Currency')</label>

                                            <input type="text" name="currency" class="form-control border-radius-5" required value="{{ old('currency') }}"/>

                                        </div>



                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-15">

                                        <div class="form-group">

                                            <label>@lang('Rate')</label>

                                            <div class="input-group">

                                                <div class="input-group-text">1 {{ __($general->cur_text )}} =</div>

                                                <input type="number" step="any" class="form-control" name="rate" required value="{{ old('rate') }}"/>

                                                <div class="input-group-text"><span class="currency_symbol"></span></div>

                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <div class="row">



                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

                                        <div class="card border--primary mt-3">

                                            <h5 class="card-header bg--primary">@lang('Range')</h5>

                                            <div class="card-body">

                                                <div class="form-group">

                                                    <label>@lang('Minimum Amount')</label>

                                                    <div class="input-group">

                                                        <input type="number" step="any" class="form-control" name="min_limit" required value="{{ old('min_limit') }}"/>

                                                        <div class="input-group-text">{{ __($general->cur_text) }}</div>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label>@lang('Maximum Amount')</label>

                                                    <div class="input-group">

                                                        <input type="number" step="any" class="form-control" name="max_limit" required value="{{ old('max_limit') }}"/>

                                                        <div class="input-group-text">{{ __($general->cur_text) }}</div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6 col-xl-6">

                                        <div class="card border--primary mt-3">

                                            <h5 class="card-header bg--primary">@lang('Charge')</h5>

                                            <div class="card-body">

                                                <div class="form-group">

                                                    <label>@lang('Fixed Charge')</label>

                                                    <div class="input-group">

                                                        <input type="number" step="any" class="form-control" name="fixed_charge" required value="{{ old('fixed_charge') }}"/>

                                                        <div class="input-group-text">{{ __($general->cur_text) }}</div>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label>@lang('Percent Charge')</label>

                                                    <div class="input-group">

                                                        <input type="number" step="any" class="form-control" name="percent_charge" required value="{{ old('percent_charge') }}">

                                                        <div class="input-group-text">%</div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <div class="col-12">

                                        <div class="card border--primary mt-3">

                                            <h5 class="card-header bg--primary">@lang('Deposit Instruction')</h5>

                                            <div class="card-body">

                                                <div class="form-group">

                                                    <textarea rows="8" class="form-control border-radius-5" name="instruction">{{ old('instruction') }}</textarea>

                                                </div>

                                            </div>

                                        </div>

                                    </div>



                                    <div class="col-lg-12">
                                        <div class="card border--primary mt-3">
                                            <h5 class="card-header bg--primary  text-white">@lang('User data')
                                                <button type="button" class="btn btn-sm btn-outline-light float-end addUserData"><i class="la la-fw la-plus"></i>@lang('Add New')
                                                </button>
                                            </h5>

                                            <div class="card-body">
                                                <div class="row addedField">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer">

                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>

                    </div>

                </form>

            </div>

        </div>

    </div>



<x-form-generator></x-form-generator>

@endsection



@push('script')

<script>

    "use strict"

    var formGenerator = new FormGenerator();

</script>



<script src="{{ asset('assets/global/js/form_actions.js') }}"></script>

@endpush



@push('breadcrumb-plugins')

    <a href="{{ route('admin.withdraw-method.index') }}" class="btn btn-sm btn-outline--primary"><i class="las la-undo"></i> @lang('Back') </a>

@endpush



@push('style')

    <style>

        .btn-sm{

            line-height:5px;

        }

    </style>

@endpush



@push('script')

    <script>

        (function ($) {

            "use strict";

            $('input[name=currency]').on('input', function () {

                $('.currency_symbol').text($(this).val());

            });



            @if(old('currency'))

            $('input[name=currency]').trigger('input');

            @endif



        })(jQuery);

    </script>

@endpush

@push('script')
    <script>

        (function ($) {
            "use strict";
            $('input[name=currency]').on('input', function () {
                $('.currency_symbol').text($(this).val());
            });
            $('.addUserData').on('click', function () {
                var html = `
                    <div class="col-md-12 user-data">
                        <div class="form-group">
                            <div class="input-group mb-md-0 mb-4">
                                <div class="col-md-4">
                                    <input name="field_name[]" class="form-control" type="text" required placeholder="@lang('Field Name')">
                                </div>
                                <div class="col-md-3 mt-md-0 mt-2">
                                    <select name="type[]" class="form-control">
                                        <option value="text" > @lang('Input Text') </option>
                                        <option value="textarea" > @lang('Textarea') </option>
                                        <option value="file"> @lang('File') </option>
                                    </select>
                                </div>
                                <div class="col-md-3 mt-md-0 mt-2">
                                    <select name="validation[]"
                                            class="form-control">
                                        <option value="required"> @lang('Required') </option>
                                        <option value="nullable">  @lang('Optional') </option>
                                        <option value="required|email"> @lang('Required Email') </option>
                                        <option value="nullable|email">  @lang('Optional Email') </option>
                                    </select>
                                </div>
                                <div class="col-md-2 mt-md-0 mt-2 text-right">
                                    <span class="input-group-btn">
                                        <button class="btn btn--danger btn-lg removeBtn w-100" type="button">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('.addedField').append(html)
            });

            $(document).on('click', '.removeBtn', function () {
                $(this).closest('.user-data').remove();
            });

            @if(old('currency'))
            $('input[name=currency]').trigger('input');
            @endif

        })(jQuery);
    </script>
@endpush