@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form action="{{route('user.withdraw.verification')}}" method="post">
                    @csrf
                    <div class="card custom--card">
                        <div class="card-header">
                            <h5 class="title">@lang('Withdraw')</h5>
                        </div>
                        <div class="card-body">
                        <h4 style="text-align: center;">{{__('available balance is')}} {{$balance}}{{$general->cur_sym}}</h4>
                            <div class="form-group">
                                <label class="form-label">@lang('Withdraw Amount') </label>
                                <input type="text" name = 'amount' required class="form-control form--control" placeholder='@lang("amount with"){{__($general->cur_text)}}'>
                                </div>
                                @if(is_array(json_decode($method->input_form, true)))
                                
                                    @foreach(json_decode($method->input_form, true) as $key => $data)
                                        <div class="form-group">
                                            <label class="form-label">@lang($data['field_level'])</label>
                                            <input type="{{ $data['type'] }}" required name="{{ $data['field_name'] }}" class="form-control form--control" placeholder="@lang('Enter') {{ __($data['field_level']) }}">
                                        </div>
                                    @endforeach

                                @endif
                                    <p>{{__($method->note)}}</p>
                            <button type="submit" class="btn btn--base w-100 mt-3">@lang('Submit')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

