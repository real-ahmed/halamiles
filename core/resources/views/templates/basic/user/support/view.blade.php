@extends($activeTemplate.'layouts.'.$layout)
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card custom--card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-10 d-flex flex-wrap align-items-center">
                                @php echo $myTicket->statusBadge; @endphp
                                <h6 class="ms-2 title">[@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}</h6>
                            </div>
                            <div class="col-sm-2 text-end">
                                @if($myTicket->status != 3 && $myTicket->user)
                                    <button class="btn btn--danger btn-sm confirmationBtn" data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i class="las la-times"></i></button>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('ticket.reply', $myTicket->id) }}" enctype="multipart/form-data" class="mb-5">
                            <div class="form-group">
                                <textarea name="message" placeholder="@lang('Your reply') ...." class="form--control">{{ old('message') }}</textarea>
                            </div>
                            <div class="form-group" >
                                @csrf
                                <div class="support-upload-field">
                                    <div class="support-upload-field__left">
                                        <label class="form-label">@lang('Select one file or multiple files')</label> <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is') {{ ini_get('upload_max_filesize') }}</small>
                                        <input class="form-control custom--file-upload" type="file" name="attachments[]">
                                        <div class="form-text text--muted">@lang('Allowed File Extensions: .jpg, .jpeg, .png, .pdf.')</div>
                                    </div>
                                    <div class="support-upload-field__right">
                                        <button type="button" class="btn btn--base add-field"><i class="las la-plus"></i></button>
                                    </div>
                                </div>
                                <div id="file-upload-list"></div>
                            </div>
                            <div class="form-group text-end">
                            <button type="submit" class="btn btn--base w-100"><i class="fa fa-reply"></i> @lang('Reply')</button>
                            </div>
                        </form>
                        @foreach($messages as $message)
                            @if($message->admin_id == 0)
                                <div class="single-reply">
                                    <div class="left">
                                        <h6>{{ $message->ticket->name }}</h6>
                                    </div>
                                    <div class="right">
                                        <span class="fst-italic fs--14px text--base mb-2">@lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</span>
                                        <p>{{$message->message}}</p>
                                        <div class="mt-2">
                                            @foreach($message->attachments as $k=> $image)
                                                <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="single-reply">
                                    <div class="left">
                                        <h6>{{ $message->admin->name }}</h6>
                                        <p class="lead text-muted">@lang('Staff')</p>
                                    </div>
                                    <div class="right">
                                        <span class="fst-italic fs--14px text--base mb-2">@lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</span>
                                        <p>{{$message->message}}</p>
                                        <div class="mt-2">
                                            @foreach($message->attachments as $k=> $image)
                                                <a href="{{route('ticket.download',encrypt($image->id))}}" class="mr-3"><i class="fa fa-file"></i>  @lang('Attachment') {{++$k}} </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div><!-- card end -->
            </div>
        </div>
    </div>
</section>
<x-confirmation-modal btnSize="btn-sm" btnBase="btn--base"></x-confirmation-modal>
@endsection
@push('style')
    <style>
        .input-group-text:focus{
            box-shadow: none !important;
        }
    </style>
@endpush
@push('script')
    <script>
        (function ($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click',function(){
                if (fileAdded >= 4) {
                    notify('error','You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input-group my-3">
                        <input type="file" name="attachments[]" class="form-control form--control" required />
                        <button class="input-group-text btn-danger remove-btn"><i class="las la-times"></i></button>
                    </div>
                `)
            });
            $(document).on('click','.remove-btn',function(){
                fileAdded--;
                $(this).closest('.input-group').remove();
            });


            $('.add-field').on('click', function(){
                if (fileAdded >= 4) {
                    notify('error','You\'ve added maximum number of file');
                    return false;
                }
                fileAdded++;
                $('#file-upload-list').append(`
                <div class="single-file-upload mt-2">
                    <input class="form-control custom--file-upload" type="file" name="attachments[]">
                    <button type="button" class="btn btn--danger remove-field"><i class="las la-times"></i></button>
                    <div class="form-text text--muted w-100">Allowed File Extensions: .jpg, .jpeg, .png, .pdf.</div>
                </div>
                `);

                $('.remove-field').on('click', function(){
                $(this).parent('.single-file-upload').remove()
                });
            });


        })(jQuery);

    </script>
@endpush
