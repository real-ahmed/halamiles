@extends('admin.layouts.app')

@section('panel')





    <div class="row">



        <div class="col-lg-12">

            <div class="card b-radius--10 ">

                <div class="card-body p-0">

                    <div class="table-responsive--md  table-responsive">



                        <table class="table table--light style--two spaced-rows">

                            <thead>

                                <tr>

                                    <th>@lang('S.N.')</th>
                                    
                                    <th>@lang('Image')</th>

                                    <th>@lang('Title')</th>

                                    <th>@lang('Status')</th>

                                    <th>@lang('Actions')</th>



                                </tr>

                            </thead>

                            <tbody>

                                @forelse($banners as $banner)
                                    <tr>

                                        <td data-label="@lang('S.N')">{{ $loop->index+1 }}</td>

                                        <td data-label="@lang('Image')">
                                            <img style="max-height: 200px;" src="{{getImage(getFilePath('banner') . $banner->img, '1920x1080')}}" alt="">
                                        </td>

                                        <td data-label="@lang('Title')">{{ __($banner->title) }}</td>

                                        <td data-label="@lang('Status')">

                                            @if ($banner->status == 1)
                                                <span
                                                    class="text--small badge font-weight-normal badge--success">@lang('Active')</span>
                                            @else
                                                <span
                                                    class="text--small badge font-weight-normal badge--warning">@lang('Inactive')</span>
                                            @endif

                                        </td>

                                    


                                        <td data-label="@lang('Action')">

                                            <button class="btn btn-sm btn-outline--primary editbanner"
                                                data-id="{{ $banner->id }}"
                                                data-name="{{ $banner->title }}"
                                                data-status="{{ $banner->status }}" 
                                                data-image="{{getImage(getFilePath('banner') . $banner->img, '1920x1080')}}"
                                                
                                                data-toggle="tooltip" data-original-title="@lang('Edit')">

                                                <i class="las la-pen text-shadow"></i> @lang('Edit')

                                            </button>

                                            <a href="{{ route('admin.frontend.banners.delete',$banner->id ) }}">
                                            <button class="btn btn-sm btn-outline--danger  deletebanner"
                                                data-id="{{ $banner->id }}"
                                                
                                                data-toggle="tooltip" data-original-title="@lang('Delete')">

                                                <i class="las la-trash text-shadow"></i> @lang('Delete')

                                            </button>
                                            </a>

                                        </td>

                                    </tr>
                                @empty

                                    <tr>

                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>

                                    </tr>
                                @endforelse



                            </tbody>

                        </table><!-- table end -->

                    </div>

                </div>

                <div class="card-footer py-4">



                </div>

            </div>

        </div>

    </div>



    {{-- banner modal --}}

    <div id="bannerModal" class="modal fade" tabindex="-1" role="dialog">

        <div class="modal-dialog" role="document">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title"></h5>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <form method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-body">

                        <div class="form-group">

                            <div class="image-upload">

                                <div class="thumb">

                                    <div class="avatar-preview">

                                        <div class="profilePicPreview"
                                            style="background-image: url({{ getImage(getFilePath('banner')) }})">

                                            <button type="button" class="remove-image"><i class="fa fa-times"></i></button>

                                        </div>

                                    </div>

                                    <div class="avatar-edit">

                                        <input type="file" class="profilePicUpload" name="image" id="profilePicUpload2"
                                            accept=".png, .jpg, .jpeg">

                                        <label for="profilePicUpload2"
                                         class="bg--success">@lang('banners Image')</label>

                                        <small class="mt-2 text-facebook">@lang('Supported files'): <b>@lang('jpeg'),
                                                @lang('jpg'), @lang('png') @lang("1920x1080").</b> </small>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="form-group">

                            <label>@lang('title')</label>

                            <input class="form-control" type="text" name="name">
                        </div>


                        <div class="form-group statusGroup">

                            <label>@lang('Status')</label>

                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                    data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status">

                        </div>






            </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>

                        </div>

        </form>

    </div>

    </div>

    </div>





@endsection



@push('breadcrumb-plugins')


        <button class="btn btn-outline--primary box--shadow1 addbanners"><i
                class="las la-plus"></i>@lang('Add New')</button>

@endpush



@push('style')
    <style>
        .btn {

            display: inline-flex;

            justify-content: center;

            align-items: center
        }

        .header-search-wrapper {

            gap: 15px
        }

        .datepickers-container {

            z-index: 1056;

        }



        @media (max-width:400px) {

            .header-search-form {

                width: 100%
            }

        }

        .right-part {

            display: flex;

            flex-direction: row;

            align-items: center;

            justify-content: flex-end;

        }







        #sortForm {

            display: flex;

            flex-direction: row;

            align-content: center;

            align-items: center;

        }



        #sort_by,

        #sort_direction {

            font-size: 17px;

            margin-right: 20px;



        }
    </style>
@endpush





@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush









@push('script')
<script>
    (function($) {
        "use strict";

        var modal = $('#bannerModal');
        var action = `{{ route('admin.frontend.banners.save') }}`;


        $('.addbanners').click(function() {
            modal.find('.modal-title').text("@lang('Add banners')");
            modal.find('.statusGroup').hide();
            modal.find('form').attr('action', action);
            modal.modal('show');
        });


        $('.editbanner').click(function() {

            var data = new $(this).data();
            modal.find('.modal-title').text("@lang('Update Banner')");
            modal.find('.statusGroup').show();
            modal.find('[name=name]').val(data.name);
            modal.find('.profilePicPreview').css('background-image', `url(${data.image})`);
            if (data.status == 1) {

                modal.find('input[name=status]').bootstrapToggle('on');

            } else {

                modal.find('input[name=status]').bootstrapToggle('off');

            }

            modal.find('form').attr('action', `${action}/${data.id}`);
            modal.modal('show');

        });



        modal.on('hidden.bs.modal', function() {

            modal.find('form')[0].reset();

            modal.find('.profilePicPreview').css('background-image',
                'url({{ getImage(getFilePath('store')) }})');

            modal.find('.profilePicUpload').val('');

            modal.find('input[type=checkbox]').prop('checked', false);




        });
        
    })(jQuery);
</script>

@endpush
