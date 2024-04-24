@extends($activeTemplate . 'layouts.frontend')

@section('content')
    @php
        $banner = getContent('banner.content', true);
        $banners = getBanners();
        
    @endphp

    <section>

        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">

            <div class="carousel-indicators">

                @foreach ($banners as $key => $banner)
                        <button data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}" @if ($key == 0) class="active" @endif></button>
                @endforeach      
            </div>
                <ol class="carousel-indicators">
                    @foreach ($banners as $key => $banner)
                        <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                            @if ($key == 0) class="active" @endif></li>
                    @endforeach
                </ol>
                @foreach ($banners as $kay => $banner)
                    <div class="carousel-item {{ $kay == 0 ? 'active' : '' }}">
                        <span class="w-100 h-100 hero-section bg_img"
                            style="background: url({{ getImage('assets/images/frontend/banner/' . $banner->img, '1920x1080') }}) center; background-size:cover; background-repeat:no-repeat;">
                            <div class="container">
                                <div class="row">
                                    <div class="col-xxl-6 col-lg-7 col-md-8 col-sm-9 col-11">
                                        <h2 class="hero-section__title">{{ __($banner->title) }}</h2>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </button>
        </div>

    </section>



    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif

@endsection

@push('script')
    <script>
        $(document).ready(function() {

            if (lang === 'ar') {
                $('#carouselExampleIndicators').carousel({
                    interval: 3000,
                    direction: "right" // This sets the auto slide direction to right for Arabic
                });
            } else {
                $('#carouselExampleIndicators').carousel({
                    interval: 3000
                });
            }
            $('a[data-slide]').click(function(e) {
                e.preventDefault();
            });
            $(".carousel-control-prev").click(function() {
                $("#carouselExampleIndicators").carousel("prev");
            });

            $(".carousel-control-next").click(function() {
                $("#carouselExampleIndicators").carousel("next");
            });

            $('#carouselExampleIndicators').on('slid.bs.carousel', function() {
                // Get the index of the current active item
                var currentIndex = $(this).find('.carousel-item.active').index();

                // Remove 'active' class from all indicators
                $(this).find('.carousel-indicators li').removeClass('active');

                // Add 'active' class to the current indicator
                $(this).find('.carousel-indicators li').eq(currentIndex).addClass('active');
            });

        });
    </script>
@endpush
