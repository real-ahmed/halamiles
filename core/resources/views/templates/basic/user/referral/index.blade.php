@extends($activeTemplate.'layouts.master')
@section('content')
<section class="pt-80 pb-80">
    <div class="container">
        <div class="table-responsive table-responsive--md">
            <table class="table custom--table">
                <thead>
                    <tr>
                        <th>@lang('S.N.')</th>
                        <th>@lang('Transaction')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Referral User')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Status')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($referrals as $referral)
                        <tr>
                            <td data-label="@lang('S.N.')">{{ $loop->iteration }}</td>
                            <td data-label="@lang('Transaction')">{{ __($referral->referrerTransaction->id) }}</td>
                            <td data-label="@lang('Date')">
                                {{ showDateTime($referral->created_at) }}<br>{{ diffForHumans($referral->created_at) }}
                            </td>
                            <td data-label="@lang('Referral User')">{{ $referral->user->fullname }}</td>
                            <td data-label="Amount">{{$referral->userTransaction->amount}}</td>
                        
                            <td data-label="@lang('Status')">
                                @if($referral->status == 0)
                                    <span class="badge badge--warning">@lang('Pending')</span>
                                @elseif($referral->status == 1)
                                    <span class="badge badge--success">@lang('Confirmed')</span>
                                @else
                                    <span class="badge badge--warning">@lang('Cancelled')</span>
                                @endif
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
        {{ $referrals->links() }}
    </div>
    <x-confirmation-modal btnSize="btn-sm" btnBase="btn--base"></x-confirmation-modal>
</section>

@endsection

