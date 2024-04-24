<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Referral;
use App\Models\GeneralSetting;

class CheckAndUpdateDepositStatus extends Command
{
    protected $signature = 'deposits:check-status';
    protected $description = 'Check and update deposit statuses';

    public function handle()
    {
        $general = GeneralSetting::first();
        $transactions = Transaction::where('status', '0')->where('type', '4')->get();

        foreach ($transactions as $transaction) {
            $totalAmount = Transaction::where('type', '5')
                ->where('user_id', $transaction->user->id)
                ->where('status', '1')
                ->where('created_at', '>', $transaction->created_at)
                ->sum('amount');

            if ($totalAmount >= $general->referral_min && $transaction->created_at->diffInDays(Carbon::now()) <= $general->referral_days) {
                $transaction->update(['status' => '1']);

                $referrerTransaction = $this->getReferrerTransaction($transaction);

                if ($referrerTransaction) {
                    $referrerTransaction->update(['status' => '1']);
                }
            } elseif ($transaction->created_at->diffInDays(Carbon::now()) >= $general->referral_days) {
                $transaction->update(['status' => '2']);

                $referrerTransaction = $this->getReferrerTransaction($transaction);

                if ($referrerTransaction) {
                    $referrerTransaction->update(['status' => '2']);
                }
            }
        }

        $this->info('Deposit statuses checked and updated successfully.');
    }

    private function getReferrerTransaction($transaction)
    {
        $referral = Referral::where('user_id', $transaction->user->id)->first();

        if ($referral) {
            return $referral->referrer->transactions()
                ->where('status', '0')
                ->where('type', '3')
                ->first();
        }

        return null;
    }
}

