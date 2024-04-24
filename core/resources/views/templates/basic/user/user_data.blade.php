@extends($activeTemplate . 'layouts.frontend')



@section('content')
    <section class="pt-80 pb-80 sections">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-md-8 col-lg-7 col-xl-5">

                    <div class="card custom--card">

                        <div class="card-header">

                            <h5 class="title">{{ __($pageTitle) }}</h5>

                        </div>



                        <div class="card-body">

                            <form method="POST" action="{{ route('user.data.submit') }}">

                                @csrf

                                <div class="row">

                                    <div class="form-group col-sm-6">

                                        <label class="form-label">@lang('First Name')</label>

                                        <input type="text" class="form-control form--control" name="firstname"
                                            value="{{ old('firstname') }}" required>

                                    </div>



                                    <div class="form-group col-sm-6">

                                        <label class="form-label">@lang('Last Name')</label>

                                        <input type="text" class="form-control form--control" name="lastname"
                                            value="{{ old('lastname') }}" required>

                                    </div>




                                    <div class="form-group col-sm-6">

                                        <label class="form-label">@lang('City')</label>

                                        <input type="text" class="form-control form--control" name="city"
                                            value="{{ old('city') }}">

                                    </div>

                                </div>

                                <div class="form-group">

                                    <button type="submit" class="btn btn--base w-100">

                                        @lang('Submit')

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>
@endsection
