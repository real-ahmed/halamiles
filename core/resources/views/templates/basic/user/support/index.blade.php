@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="text-end">
            <a href="{{ route('ticket.open') }}" class="btn btn-sm btn--base mb-2"> <i
                    class="fa fa-plus"></i> @lang('New Ticket')</a>
        </div>
        <div class="table-responsive table-responsive--md">
            <table class="table custom--table">
                <thead>
                    <tr>
                        <th>@lang('Subject')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Priority')</th>
                        <th>@lang('Last Reply')</th>
                        <th>@lang('Action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supports as $key => $support)
                        <tr>
                            <td data-label="@lang('Subject')">
                                <a href="{{ route('ticket.view', $support->ticket) }}">[@lang('Ticket')#{{ $support->ticket }}]</a>
                                <span>{{ __($support->subject) }}</span>
                            </td>
                            <td data-label="@lang('Status')">
                                @php echo $support->statusBadge; @endphp
                            </td>
                            <td data-label="@lang('Priority')">
                                @if($support->priority == 1)
                                    <span class="badge badge--dark">@lang('Low')</span>
                                @elseif($support->priority == 2)
                                    <span class="badge badge--success">@lang('Medium')</span>
                                @elseif($support->priority == 3)
                                    <span class="badge badge--primary">@lang('High')</span>
                                @endif
                            </td>
                            <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }}</td>
                            <td data-label="@lang('Action')">
                                <a href="{{ route('ticket.view', $support->ticket) }}" class="icon-btn bg--base" data-bs-toggle="tooltip"
                                    data-bs-position="top" title="@lang('Ticket View')"><i class="las la-desktop"></i></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $supports->links() }}
    </div>
</section>
@endsection
