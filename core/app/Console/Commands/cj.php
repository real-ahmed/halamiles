<?php

namespace App\Console\Commands;

use App\Models\Click;
use App\Models\ClickTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CJ extends Command
{
    protected $signature = 'import:cj {startDate? : Start date in Y-m-d format} {endDate? : End date in Y-m-d format}';
    protected $description = 'Import data from CJ API';

    public function handle()
    {
        $developerKey = "61xegh2xsw66996mq7csjv572a";
        $websiteID = "5284959";
        $startDate = $this->argument('startDate') ?: Carbon::now()->subDays(20)->format('Y-m-d');
        $endDate = $this->argument('endDate') ?: date('Y-m-d');

        $feedURL = 'https://commission-detail.api.cj.com/v3/commissions?requestor-cid=' . $websiteID;
        $feedURL .= '&date-type=posting&';
        $feedURL .= 'start-date=' . $startDate . '&';
        $feedURL .= 'end-date=' . $endDate . '&';
        $feedURL .= 'website-ids=' . $websiteID;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $developerKey,
            'User-Agent' => '"Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.15) Gecko/2009101601 Firefox/3.0.15 GTB6 (.NET CLR 3.5.30729)"'
        ])->get($feedURL);

        if ($response->successful()) {
            $responseData = simplexml_load_string($response->body())->commissions;
            var_dump($responseData);
            $this->parseData($responseData);
            $this->info('Data parsed and saved successfully');
        } else {
            $this->error('No data found');
        }
    }

    private function parseData($data)
    {
        foreach ($data->commission as $transaction) {
            // Similar logic to your Awin class, adapt as needed
            $clickRef = (string)$transaction->sid;
            $click = Click::where('id', $clickRef)->first();

            if ($click) {
                $cashback_type = $click->model->cashbacktype->id;
                $cashback = $click->model->cashback;
                $amount = 0;
                $status = 0;


                if ($cashback_type == 1) {
                    $amount = (float)$cashback;
                } elseif ($cashback_type == 2) {
                    $orderAmount = (float)preg_replace('~[^0-9.,]~', '', $transaction['sale-amount']);
                    $orderAmount = round($orderAmount, 2);
                    $amount = ((float)$click->model->user_percentage / 100) * $orderAmount;
                    $cashback_type = 1;
                }

                if ((string)$transaction['action-status'] === 'D' || (string)$transaction['action-status'] === 'F') {
                    $status = 2;
                } elseif ((string)$transaction['action-status'] === 'A') {
                    $status = 1;
                }

                $siteTransaction = ClickTransaction::where('network_transaction_id', (int)$transaction['action-tracker-id'])
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
                    $clickTransaction = new ClickTransaction;
                    $clickTransaction->click_id = $click->id;
                    $clickTransaction->transaction_id = $newSiteTransaction->id;
                    $clickTransaction->network_transaction_id = (int)$transaction['action-tracker-id'];
                    $clickTransaction->category_rate = $transaction['action-tracker-name'];
                    $clickTransaction->save();
                }
            }
        }
    }
}
