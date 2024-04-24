@extends('admin.layouts.app')

@section('panel')

    <div class="row mb-none-30 justify-content-center">

        <div class="col-xl-4 col-md-6 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">

                <div class="card-body">

                    <h5 class="mb-20 text-muted">@lang('Transaction no') {{$transaction->id }}</h5>

                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            @lang('Date')

                            <span class="fw-bold">{{ showDateTime($transaction->created_at) }}</span>

                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            @lang('Username')

                            <span class="fw-bold">

                                <a href="{{ route('admin.users.detail', $transaction->user_id) }}">{{ @$transaction->user->username }}</a>

                            </span>

                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            @lang('Type')

                            <span class="fw-bold">{{$transaction->type}}</span>

                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">

                            @lang('Amount')

                            <span class="fw-bold">{{ showAmount($transaction->amount ) }} {{ __($general->cur_text) }}</span>

                        </li>



                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Status')
                                @if ($transaction->status == 0)
                                    <span
                                        class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                @elseif($transaction->status == 1)
                                    <span
                                        class="text--small badge font-weight-normal badge--success">@lang('Confirmed')</span>
                                @else
                                    <span
                                        class="text--small badge font-weight-normal badge--Danger">@lang('Cancelled')</span>
                                @endif

                        </li>


                    </ul>

                </div>

            </div>

        </div>






@endsection




