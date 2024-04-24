@extends('admin.layouts.app')



@section('panel')

<div class="row justify-content-center">

    @if(request()->routeIs('admin.referrals.all'))
    <div class="col-xxl-3 col-sm-6 mb-30">

            <div class="widget-two box--shadow2 b-radius--5 bg--6 has-link">

                <a href="" class="item-link"></a>

                <div class="widget-two__content">

                    <h2 class="text-white">{{ $total }}</h2>

                    <p class="text-white">@lang('Total Referrals')</p>

                </div>

            </div><!-- widget-two end -->

        </div>
        <div class="col-xxl-3 col-sm-6 mb-30">

            <div class="widget-two box--shadow2 b-radius--5 bg--success has-link">

                <a href="" class="item-link"></a>

                <div class="widget-two__content">

                    <h2 class="text-white">{{ $confirmed }}</h2>

                    <p class="text-white">@lang('Confirmed Referrals')</p>



                </div>

            </div><!-- widget-two end -->

        </div>



        
        <div class="col-xxl-3 col-sm-6 mb-30">
            
            <div class="widget-two box--shadow2 has-link b-radius--5 bg--dark">
                
                <a href="" class="item-link"></a>
                
                <div class="widget-two__content">
                    
                    <h2 class="text-white">{{ $pending }}</h2>
                    
                    <p class="text-white">@lang('Pending Referrals')</p>
                    
                </div>
                

            </div><!-- widget-two end -->

        </div>


        <div class="col-xxl-3 col-sm-6 mb-30">
        
        <div class="widget-two box--shadow2 has-link b-radius--5 bg--pink">

            <a href="" class="item-link"></a>

            <div class="widget-two__content">
                <h2 class="text-white">{{ $cancelled }}</h2>

                <p class="text-white">@lang('Cancelled Referrals')</p>


            </div>

        </div><!-- widget-two end -->

    </div>

    @endif



    <div class="col-md-12">

        <div class="card b-radius--10">

            <div class="card-body p-0">

                <div class="table-responsive--sm table-responsive">

                    <table class="table table--light style--two">

                        <thead>

                        <tr>

                            <th>@lang('Transaction')</th>
                            
                            <th>@lang('Date')</th>

                            <th>@lang('Referral User')</th>

                            <th>@lang('Referral Amount')</th>

                            <th>@lang('Referrer User')</th>

                            <th>@lang('Referrer Amount')</th>

                            <th>@lang('Status')</th>

                            <!-- <th>@lang('Action')</th> -->

                        </tr>

                        </thead>

                        <tbody>

                        @forelse($referrals as $referral)



                            <tr>

                                <td data-label="@lang('Transactions')">

                                     <span class="fw-bold"> <a href="">{{$referral->referrerTransaction->id}}</a> </span>

                                     <br>

                                     <span class="fw-bold"> <a href="">{{$referral->userTransaction->id}}</a> </span>

                                </td>



                                <td data-label="@lang('Date')">

                                    {{ showDateTime($referral->created_at) }}<br>{{ diffForHumans($referral->created_at) }}

                                </td>

                                <td data-label="@lang('Referral User')">

                                    <span class="fw-bold">{{ $referral->user->fullname }}</span>

                                    <br>

                                    <span class="small">

                                    <a href="{{route('admin.users.detail',$referral->user->id)}}"><span>@</span>{{ $referral->user->username }}</a>

                                    </span>

                                </td>

                                <td data-label="@lang('Referral Amount')">

                                {{$referral->userTransaction->amount}}

                                </td>

                                <td data-label="@lang('Referrer User')">

                                    <span class="fw-bold">{{ $referral->referrer->fullname }}</span>

                                    <br>

                                    <span class="small">

                                    <a href="{{route('admin.users.detail',$referral->referrer->id)}}"><span>@</span>{{ $referral->referrer->username }}</a>

                                    </span>

                                </td>


                                <td data-label="@lang('Referrer Amount')">

                                {{$referral->referrerTransaction->amount}}

                                </td>



                                <td data-label="@lang('status')">

                                @if ($referral->status == 0)
                                    <span
                                        class="text--small badge font-weight-normal badge--warning">@lang('Pending')</span>
                                @elseif($referral->status == 1)
                                    <span
                                        class="text--small badge font-weight-normal badge--success">@lang('Confirmed')</span>
                                @else
                                    <span
                                        class="text--small badge font-weight-normal badge--Danger">@lang('Cancelled')</span>
                                @endif


                                </td>


                                <!-- <td data-label="@lang('Action')">

                                    <a href=""

                                       class="btn btn-sm btn-outline--primary ms-1">

                                        <i class="la la-desktop"></i> @lang('Details')

                                    </a>

                                </td> -->

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

            @if ($referrals->hasPages())

            <div class="card-footer py-4">

                {{ paginateLinks($referrals) }}

            </div>

            @endif

        </div><!-- card end -->

    </div>

</div>





@endsection





@push('breadcrumb-plugins')

    @if(!request()->routeIs('admin.users.referrals') && !request()->routeIs('admin.users.referrals.method'))

        <form action="" method="GET">

            <div class="form-inline float-sm-end mb-2 ms-0 ms-xl-2 ms-lg-0">

                <div class="input-group">

                    <input type="text" name="search" class="form-control bg--white" placeholder="@lang('Trx number/Username')" value="{{ request()->search ?? '' }}">

                    <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>

                </div>

            </div>

            <div class="form-inline float-sm-end">

                <div class="input-group">

                    <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control bg--white" data-position='bottom right' placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">

                    <button class="btn btn--primary input-group-text" type="submit"><i class="fa fa-search"></i></button>

                </div>

            </div>

        </form>

    @endif

@endpush





@push('script-lib')

  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>

  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>

@endpush

@push('script')

  <script>

    (function($){

        "use strict";

        if(!$('.datepicker-here').val()){

            $('.datepicker-here').datepicker();

        }

    })(jQuery)

  </script>

@endpush

