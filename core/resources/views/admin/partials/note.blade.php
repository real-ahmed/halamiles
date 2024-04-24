<div class="modal fade" id="storeNotes">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <p class="mt-2 fw-bold">@lang('Notes'): <span class="discount-amount"></span></p>
                </div>
                <div class='notes-box'>
                </div>
                <div class='btn-section'>
                    <button data-bs-dismiss="modal" >@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
(function($){
  "use strict";

 $('.notes').click(function () {
  var modal = $('#storeNotes');

  modal.modal('show');
});


  var x = null;
  $('#storeNotes').on('hidden.bs.modal', function() {
    clearInterval(x);
  });

  
})(jQuery);


    </script>
@endpush


