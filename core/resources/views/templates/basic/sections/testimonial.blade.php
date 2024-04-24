@php
    $testimonial = getContent('testimonial.content', true);
    $testimonials = getContent('testimonial.element', false, null, true);
    $counters = getContent('counter.element', false, null, true);
@endphp

<section class="pt-80 pb-80 section--bg2 sections overflow-hidden">
    <div class="container">
        <div class="row gy-4">
            <div class="col-xl-4 text-xl-start text-center">
                <h2 class="section-title text-white">{{ __($testimonial->data_values->heading) }}</h2>
                <p class="text-white mt-3">{{ __($testimonial->data_values->subheading) }}</p>
                <div class="row gy-3 justify-content-xl-start justify-content-center mt-4">
                    @foreach ($counters as $counter)
                        <div class="col-xl-4 col-lg-3 col-sm-4">
                            <div class="counter-item">
                                <h4 class="counter-item__amount">{{ $counter->data_values->counter_digit }}</h4>
                                <p class="text-white">{{ __($counter->data_values->title) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-xl-8">
                <div class="testimonial-slider">
                    @foreach ($testimonials as $testimonial)
                        <div class="single-slide">
                            <div class="testimonial-item">
                                <div class="testimonial-item__quote">
                                    @php echo $testimonial->data_values->icon @endphp
                                </div>
                                <p class="testimonial-item__details">{{ __($testimonial->data_values->message) }}</p>
                                <h6 class="mt-4">{{ __($testimonial->data_values->name) }}</h6>
                                <span>{{ __($testimonial->data_values->designation) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
