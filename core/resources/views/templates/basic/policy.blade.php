@extends($activeTemplate.'layouts.frontend')

@section('content')

<section class="pt-80 pb-80">

    <div class="container">

        @php

            echo $policy->data_values->details

        @endphp

    </div>

</section>

@endsection

