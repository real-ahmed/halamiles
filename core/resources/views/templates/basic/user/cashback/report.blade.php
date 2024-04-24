@extends($activeTemplate.'layouts.master')

@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row">
            <div class="col-xl-4">
                <div class="card custom--card">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Last 30 Days Report')</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Total Impression')
                                <span>{{ $coupon->reports->where('action', 'impression')->sum('number') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Total Click')
                                <span>{{ $coupon->reports->where('action', 'click')->sum('number') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Total Copy')
                                <span>{{ $coupon->reports->where('action', 'copy')->sum('number') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Unique Impression')
                                <span>{{ $coupon->reports->where('action', 'impression')->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Unique Click')
                                <span>{{ $coupon->reports->where('action', 'click')->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Unique Copy')
                                <span>{{ $coupon->reports->where('action', 'copy')->count() }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card custom--card mt-5">
                    <div class="card-header">
                        <h5 class="card-title">@lang('Full Report')</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Impression')
                                <span>{{ $coupon->impression }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Click')
                                <span>{{ $coupon->click }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Copy')
                                <span>{{ $coupon->copy }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card custom--card mt-5 mt-xl-0">
                  <div class="card-body">
                    <h5 class="card-title">{{ $coupon->title }} (@lang('Report Last 30 Days'))</h5>
                    <div id="apex-line"></div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@push('script-lib')
    <script src="{{asset('assets/admin/js/vendor/apexcharts.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendor/chart.js.2.8.0.js')}}"></script>
@endpush

@push('script')
    <script>
    (function ($) {
    "use strict";
        // apex-line chart
        var options = {
        chart: {
            height: 450,
            type: "area",
            toolbar: {
            show: false
            },
            dropShadow: {
            enabled: true,
            enabledSeries: [0],
            top: -2,
            left: 0,
            blur: 10,
            opacity: 0.08
            },
            animations: {
            enabled: true,
            easing: 'linear',
            dynamicAnimation: {
                speed: 1000
            }
            },
        },
        dataLabels: {
            enabled: false
        },
        series: [
            {
            name: "Impression",
            data: [
                @foreach($couponReport['date'] as $reportDate)
                    {{ @$impression->where('date',$reportDate)->first()->amount ?? 0 }},
                @endforeach
            ]
            },
            {
            name: "Click",
            data: [
                    @foreach($couponReport['date'] as $reportDate)
                        {{ @$click->where('date',$reportDate)->first()->amount ?? 0 }},
                    @endforeach
                ]
            },
            {
            name: "Copy",
            data: [
                    @foreach($couponReport['date'] as $reportDate)
                        {{ @$copy->where('date',$reportDate)->first()->amount ?? 0 }},
                    @endforeach
                ]
            }
        ],
        fill: {
            type: "gradient",
            gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.7,
            opacityTo: 0.9,
            stops: [0, 90, 100]
            }
        },
        xaxis: {
            categories: [
                @foreach($couponReport['date'] as $reportDate)
                    "{{ $reportDate }}",
                @endforeach
            ]
        },
        grid: {
            padding: {
            left: 5,
            right: 5
            },
            xaxis: {
            lines: {
                show: false
            }
            },
            yaxis: {
            lines: {
                show: false
            }
            },
        },
        };
        var chart = new ApexCharts(document.querySelector("#apex-line"), options);
        chart.render();
    })(jQuery);
    </script>
@endpush
