@extends($activeTemplate . 'layouts.master')



@section('content')

    <section class="pt-80 pb-80 section--bg sections">

        <div class="container">

            <div class="d-flex flex-wrap gap-4">

                @forelse ($stores as $store)

                    <div class="store-item text-center has--link flex-shrink-0">

                        <a href="{{ route('store', ['id' => $store->id]) }}">

                        <div class="store-item__thumb">

                        <span class='fav-counter'>

                            <i class="fa-heart  fas"></i>

                            <span>{{$store->favorite->count()}}</span>

                        </span>

                            <img src="{{ getImage(getFilePath('store') . '/' . $store->image) }}"

                                alt="image">

                        </div>

                        <div class="store-item__content">

                            <div class="d-flex flex-wrap align-items-center justify-content-center text--base">

                                @if($store->cashback != 0)

                                <h3 class="me-2">{{$store->cashback}}{{$store->cashbacktype->sign}}</h3>

                                <span>{{$store->cashbacktype->id != 3 ? __('Cash Back Up To') : __('HalaMiles Up To')}}</span>
                                @else
                                
                                <h4 class="me-2">{{_('View store offers')}}</h4>
                                @endif

                            </div>

                        </div>

                        </a>

                    </div>

                @empty

                    <div class="text-center">

                        {{ __($emptyMessage) }}

                    </div>

                @endforelse

            </div>

            {{ $stores->links() }}

        </div>

    </section>

@endsection





@push('style')

    <style>

        .store-item {

            width: calc((100% / 5) - 20px)



        }

        @media (max-width:1199px) {

            .store-item {

                width: calc((100% / 4) - 18px)

            }

        }

        @media (max-width: 767px) {

            .store-item {

                width: calc((100% / 3) - 16px)

            }

        }

        @media (max-width: 480px) {

            .store-item {

                width: calc((100% / 2) - 12px)

            }

        }

        @media (max-width: 400px) {

            .store-item {

                width: 100%

            }

        }

    </style>

@endpush