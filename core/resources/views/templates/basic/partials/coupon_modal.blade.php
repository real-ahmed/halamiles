<div class="modal fade" id="couponModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="" class="modal-store-logo" alt="image">
                    <h4 class="mt-5 title"></h4>
                    <p class="mt-2 fw-bold"><span class="discount-amount"></span></p>
                    <p class="">@lang('Copy and paste this code at') <a href="" target="_blank"
                            class="text--base store-name"></a></p>

                </div>

                <form class="coupon-copy-form my-4" data-copy=true>
                    <input type="text" value="" id="coupon-text">
                    <button type="button" class="text-copy-btn copy-btn" data-bs-toggle="tooltip"
                        data-bs-original-title="@lang('Copy to clipboard')">@lang('Copy')</button>
                </form>
                <div class="coupon-code-div">
                    <a class='go' target="_blank" href='' blank>@lang('Get the offer')</a>
                </div>

                <div class="text-center mb-4">
                    <h6>@lang('Details')</h6>
                    <p class="text--base">@lang('Ending Date:') <span class="ending_date"></span></p>
                    <p class="description"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        (function($) {
            "use strict"

            $(document).on('click', '.coupon-details', function() {
                var modal = $('#couponModal');
                var data = $(this).data();
                var coupon = data.coupon;

                modal.find('.title').text(coupon.title);
                modal.find('.discount-amount').text(data.cashback);
                modal.find('.store-name').text(coupon.store.name);
                modal.find('.store-name').attr('href', data.store_url);
                modal.find('.modal-store-logo').attr('src', data.store_image);
                modal.find('#coupon-text').val(coupon.coupon_code);
                modal.find('.copy-btn').attr('data-coupon_id', coupon.id);

                modal.find('.description').text(coupon.description);

                if (coupon.coupon_code) {
                    modal.find('.coupon-copy-form').show();
                    modal.find('.coupon-code-div').hide();
                    modal.find('.copy-btn').attr('data-url', data.coupon_url);
                } else {
                    modal.find('.coupon-copy-form').hide();
                    modal.find('.coupon-code-div').show();
                    modal.find('.go').attr('href', data.coupon_url);
                }
                modal.modal('show');

                runCountDown(coupon.ending_date);

                var clickUrl = '{{ route('coupon.view.save') }}';

                $.ajax({
                    type: "POST",
                    url: clickUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        couponId: $(this).data('coupon').id
                    }
                });

            });


            var x = null;

            function runCountDown(date) {
                var countDownDate = new Date(date).getTime();
                x = setInterval(function() {
                    var now = new Date().getTime();
                    var distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    $('.ending_date').text(days + " {{ __('days') }} " + hours + " {{ __('hr') }} " +
                        minutes +
                        " {{ __('mn') }}");

                }, 500);
            }

            $('#couponModal').on('hidden.bs.modal', function() {
                clearInterval(x);
            });

            $(document).on('click', '.copy-btn', function() {
                var copyUrl = '{{ route('coupon.copy.save') }}'
                var token = $('[name=_token').val();

                $.ajax({
                    type: "POST",
                    url: copyUrl,
                    data: {
                        _token: '{{ csrf_token() }}',
                        couponId: $(this).data('coupon_id')
                    }
                });
                // if($(this).data('url')){
                //     window.open($(this).data('url'), '_blank');
                // }
            });

        })(jQuery);
    </script>
@endpush


@push('style')
    <style>
        .coupon-code-div a {
            padding: 10px 60px;
            -webkit-border-radius: 999px;
            -moz-border-radius: 999px;
            -ms-border-radius: 999px;
            -o-border-radius: 999px;
            background: #7f7f7f;
            color: white;
        }

        .coupon-code-div a:hover {
            color: white !important;
        }

        .coupon-code-div {
            display: flex;
            justify-content: center;
            margin: 20px;
        }
    </style>
@endpush
