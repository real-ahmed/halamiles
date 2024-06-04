<div class="modal fade" id="storeTerms">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="" class="modal-store-logo" alt="image">
                    <h4 class="mt-5 title"></h4>
                    <p class="mt-2 fw-bold">@lang('Terms & Conditions'): <span class="discount-amount"></span></p>
                </div>
                <div>
                    <p class="terms" style="max-width: 100%; white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;"></p>
                </div>
                <div class="text-center">
                    <p class="mt-2 fw-bold">@lang('Accepted with'): <span class="discount-amount"></span></p>
                </div>
                <div>
                    <div style="display: flex; flex-direction: row; justify-content: center; margin-top: 5px;" class="channels">
                    </div>
                </div>
                <div class="text-center">
                    <p class="mt-2 fw-bold">@lang('Accepted Withdrawal Methods'): <span class="discount-amount"></span></p>
                </div>
                <div>
                    <div style="display: flex; flex-direction: row; justify-content: center; margin-top: 5px;"  class="withdraw-methods text-center mt-3">
                        <ul>

                        </ul>
                    </div>
                </div>
                <div class="btn-section text-center mt-3">
                    <button data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')

<style>

    .channels i {

    margin: 6px;

    font-size: 34px;

    }

</style>

@endpush

@push('script')
    <script>
        (function($){
            "use strict";

            $(document).on('click', '.store-terms', function(){
                var modal = $('#storeTerms');
                var data = $(this).data();

                modal.find('.modal-store-logo').attr('src', data.store_image);
                modal.find('.terms').text(data.terms);

                var channelsDiv = modal.find('.channels');
                channelsDiv.empty();
                for (var i = 0; i < data.channels.length; i++) {
                    var channel = data.channels[i].icon;
                    channelsDiv.append(channel);
                }

                var withdrawMethodsDiv = modal.find('.withdraw-methods ul');
                withdrawMethodsDiv.empty();
                console.log(data.withdraw_methods);
                if (data.withdraw_methods && data.withdraw_methods.length > 0) {
                    data.withdraw_methods.forEach(function(method) {
                        withdrawMethodsDiv.append('<li>' + method + '</li>');
                    });
                }

                modal.modal('show');
            });

            var x = null;
            $('#storeTerms').on('hidden.bs.modal', function() {
                clearInterval(x);
            });
        })(jQuery);
    </script>
@endpush

