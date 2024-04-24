@extends($activeTemplate.'layouts.frontend')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        @php
            echo $cookie->data_values->description
        @endphp
    </div>
</section>
@endsection
