<?php

namespace App\Console\Commands;

use App\Models\AcceptedCashoutMethod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Click;
use App\Models\Transaction;
use App\Models\ClickTransaction;

class awin extends Command
{
    protected $signature = 'import:awin {startDate? : Start date in Y-m-d format} {endDate? : End date in Y-m-d format}';
    protected $description = 'Import data from Awin API';

    public function handle()
    {
        $startDate = $this->argument('startDate') ?: Carbon::now()->subDays(20)->format('Y-m-d');
        $endDate = $this->argument('endDate') ?: date('Y-m-d');

        $startDateTime = new \DateTime($startDate . 'T00:00:00');
        $endDateTime = new \DateTime($endDate . 'T23:59:59');

        $startFormatted = $startDateTime->format('Y-m-d\TH:i:s');
        $endFormatted = $endDateTime->format('Y-m-d\TH:i:s');

        $feedURL = "https://api.awin.com/publishers/728469/transactions/?startDate=" . $startFormatted . "&endDate=" . $endFormatted . "&timezone=UTC&accessToken=71a60863-3e28-48a1-8b6b-5867cd0e5d83";

        $response = Http::get($feedURL);
        $responseData = $response->json();

        if (!empty($responseData)) {
            var_dump($responseData);
            $this->parseData($responseData);
            $this->info('Data parsed and saved successfully');
        } else {
            $this->error('No data found');
        }
    }

    private function parseData(array $data)
    {
        foreach ($data as $transaction) {
            $clickRef = (string) $transaction['clickRefs']['clickRef'];
            $click = Click::where('id', $clickRef)->first();

            if ($click) {
                $cashback_type = $click->model->cashbacktype->id;
                $cashback = $click->model->cashback;
                $amount = 0;
                $status = 0;



                if ($cashback_type == 1 || $cashback_type == 3) {
                    $amount = (float) $cashback;
                } elseif ($cashback_type == 2) {
                    $orderAmount = (float) preg_replace('~[^0-9.,]~', '', $transaction['saleAmount']['amount']);
                    $orderAmount = round($orderAmount, 2);
                    $amount = ((float) $click->model->user_percentage / 100) * $orderAmount;
                    $amount = convertCurrency($amount, $transaction['saleAmount']['currency']);
                    $cashback_type = 1;
                }

                if ((string) $transaction['commissionStatus'] === 'reject' || (string) $transaction['commissionStatus'] === 'failed' || (string) $transaction['commissionStatus'] === 'declined' || (string) $transaction['commissionStatus'] === 'deleted') {
                    $status = 2;
                } elseif ((string) $transaction['commissionStatus'] === 'approved') {
                    $status = 1;
                }

                $siteTransaction = ClickTransaction::where('network_transaction_id', (int) $transaction['id'])
                    ->where('click_id', $clickRef)
                    ->first();

                if ($siteTransaction) {
                    Transaction::where('id', $siteTransaction->transaction_id)
                        ->update(['status' => $status]);
                } else {
                    $newSiteTransaction = new Transaction;
                    $newSiteTransaction->user_id = $click->user_id;
                    $newSiteTransaction->amount = $amount;
                    $newSiteTransaction->type = 5;
                    $newSiteTransaction->cashbacktype_id = $cashback_type;
                    $newSiteTransaction->status = $status;
                    $newSiteTransaction->save();
                    $acceptedWithdrawMethods = $click->model->withdrawMethods->pluck('id')->toArray();
                    foreach ($acceptedWithdrawMethods as $methodId) {
                        AcceptedCashoutMethod::create(
                            ['withdraw_method_id'=>$methodId,'transaction_id'=>$newSiteTransaction->id]
                        );
                    }
                    $clickTransaction = new ClickTransaction;
                    $clickTransaction->click_id = $click->id;
                    $clickTransaction->transaction_id = $newSiteTransaction->id;
                    $clickTransaction->network_transaction_id = (int) $transaction['id'];
                    $clickTransaction->category_rate = $transaction['type'];

                    $clickTransaction->save();
                }
            }
        }
    }
}
