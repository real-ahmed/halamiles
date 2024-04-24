@php
    $blog   = getContent('blog.content', true);
    $blogs  = getContent('blog.element', false, 3);
@endphp
<section class="pt-80 pb-80 sections">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center gap-3">
            <h2 class="section-title">{{ __($blog->data_values->heading) }}</h2>
            <a href="{{ route('blog') }}" class="btn btn--base btn-md flex-shrink-0">@lang('View All')</a>
        </div>
        <div class="row gy-4 justify-content-center">
            @foreach ($blogs as $blog)
            <div class="col-lg-4 col-md-6">
                <div class="blog-item">
                    <div class="blog-item__thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/thumb_'.$blog->data_values->blog_image, '425x425') }}" alt="image">
                    </div>
                    <div class="blog-item__content">
                        <span class="mb-2"><i class="far fa-calendar-alt me-1"></i>{{ showDateTime($blog->created_at, 'd-m-Y') }}</span>
                        <h4 class="title"><a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}">{{ __($blog->data_values->title) }}</a></h4>
                        <p class="mt-3">
                            @php
                                echo strLimit(strip_tags($blog->data_values->description), 130);
                            @endphp
                        </p>
                        <a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}" class="read-more-btn mt-3">@lang('Read More')<i
                                class="fas fa-long-arrow-alt-right ms-2"></i></a>
                    </div>
                </div><!-- blog-item end -->
            </div>
            @endforeach
        </div>
    </div>
</section>
