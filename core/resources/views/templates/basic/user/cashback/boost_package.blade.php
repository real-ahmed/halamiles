@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            @forelse ($packages as $package)
                <div class="col-xxl-3 col-lg-3 col-md-4">
                    <div class="custom--card">
                        <div class="card-header">
                            <h5>{{ __($package->name) }}</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Price')
                                <span>{{ $general->cur_sym }}{{ showAmount($package->price) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                @lang('Duration')
                                <span>{{ $package->duration }} @lang('Days')</span>
                                </li>
                            </ul>
                            <button class="btn btn--base w-100 mt-3 boostBtn" data-package_id="{{ $package->id }}" data-coupon_id="{{ $couponId }}">@lang('Boost Now')</button>
                           
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    {{ __($emptyMessage) }}
                </div>
            @endforelse
        </div>
        {{ $packages->links() }}
    </div>
</section>

<div id="boostModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('user.coupon.boost.process') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>@lang('Are you sure to purchase this package?')</p>
                </div>
                <input type="hidden" name="package_id">
                <input type="hidden" name="coupon_id">
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark btn-sm" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn btn--base btn-sm">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')

<script>
    (function ($) {
        "use strict";
        $(document).on('click','.boostBtn', function () {
            var modal   = $('#boostModal');
            let data    = $(this).data();
            modal.find('[name=package_id]').val(data.package_id);
            modal.find('[name=coupon_id]').val(data.coupon_id);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush