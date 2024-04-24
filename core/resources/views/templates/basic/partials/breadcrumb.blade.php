@php
    $breadcrumb = getContent('breadcrumb.content', true);
@endphp
<section class="inner-hero bg_img" style="background-image: url({{ getImage('assets/images/frontend/breadcrumb/'.$breadcrumb->data_values->background_image, '1920x720') }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="title text-white">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
