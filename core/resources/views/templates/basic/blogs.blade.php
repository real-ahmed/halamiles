@extends($activeTemplate.'layouts.frontend')

@section('content')
<section class="pt-80 pb-80 sections">
    <div class="container">
        <div class="row justify-content-center gy-4">
            @forelse ($blogs as $blog)
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
                            <a href="{{ route('blog.details', [slug($blog->data_values->title), $blog->id]) }}" class="read-more-btn mt-3">Read More<i
                                    class="fas fa-long-arrow-alt-right ms-2"></i></a>
                        </div>
                    </div><!-- blog-item end -->
                </div>
            @empty
                <div class="text-center">{{ __($emptyMessage) }}</div>
            @endforelse
        </div>
        {{ $blogs->links() }}
    </div>
</section>

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection