<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Click;
use App\Models\Transaction;
use App\Models\ClickTransaction;

class hasoffers extends Command
{
    protected $signature = 'import:hasoffers {networkId? : Network Id} {apiKey? : Api kay}  {startDate? : Start date in Y-m-d format} {endDate? : End date in Y-m-d format}';
    protected $description = 'Import data from Awin API';

    public function handle()
    {
        $networkId = $this->argument('networkId') ?: '';
        $apiKey = $this->argument('apiKey') ?: '';

        $startDate = $this->argument('startDate') ?: Carbon::now()->subDays(20)->format('Y-m-d');
        $endDate = $this->argument('endDate') ?: date('Y-m-d');

        $startDateTime = new \DateTime($startDate . 'T00:00:00');
        $endDateTime = new \DateTime($endDate . 'T23:59:59');

        $startFormatted = $startDateTime->format('Y-m-d\TH:i:s');
        $endFormatted = $endDateTime->format('Y-m-d\TH:i:s');

        $feedURL = "https://" . $networkId . ".api.hasoffers.com/Apiv3/json";
        $requestData = [
            "api_key" => $apiKey,
            "Target" => "Affiliate_Report",
            "Method" => "getConversions",
            "fields" => ["PayoutGroup.name", "Offer.name", "Stat.affiliate_info1", "Goal.name", "Stat.conversion_status", "Stat.currency", "Stat.ad_id", "OfferUrl.name", "OfferUrl.id", "PayoutGroup.id", "Stat.date", "Stat.approved_payout", "OfferUrl.preview_url", "Stat.sale_amount"],
            "filters" => [
                "Stat.datetime" => [
                    "conditional" => "BETWEEN",
                    "values" => [$startFormatted, $endFormatted]
                ]
            ],
            "sort" => [
                "Stat.year" => "asc"
            ]
        ];

        $response = Http::get($feedURL, $requestData);
        $responseData = $response->json();

        if (isset($responseData['response']['errorMessage'])) {
            $this->error("API Error: " . $responseData['response']['errorMessage']);
            return;
        }

        if (!empty($responseData['response']['data'])) {
            var_dump($responseData['response']['data']);
            $this->parseData($responseData['response']['data']);
            $this->info('Data parsed and saved successfully');
        } else {
            $this->error('No data found');
        }
    }


    private function parseData(array $data)
    {


        foreach ($data as $transaction) {
            $clickRef = (string) $transaction['Stat']['affiliate_info1'];
            $click = Click::where('id', $clickRef)->first();

            if ($click) {
                $cashback_type = $click->model->cashbacktype->id;
                $cashback = $click->model->cashback;
                $amount = 0;
                $status = 0;



                if ($cashback_type == 1 || $cashback_type == 3) {
                    $amount = (float) $cashback;
                } elseif ($cashback_type == 2) {
                    $orderAmount = (float) preg_replace('~[^0-9.,]~', '', $transaction['Stat']['sale_amount']);
                    $orderAmount = round($orderAmount, 2);
                    $amount = ((float) $cashback / 100) * $orderAmount;
                    $amount = convertCurrency($amount, $transaction['Stat']['currency']);
                    $cashback_type = 1;
                }

                if ((string) $transaction['Stat']['conversion_status'] === 'reject' || (string) $transaction['Stat']['conversion_status'] === 'Failed' || (string) $transaction['Stat']['conversion_status'] === 'Denied' || (string) $transaction['Stat']['conversion_status'] === 'deleted') {
                    $status = 2;
                } elseif ((string) $transaction['Stat']['conversion_status'] === 'Confirmed') {
                    $status = 1;
                }

                $siteTransaction = ClickTransaction::where('click_id', $clickRef)->first();

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
                    $clickTransaction->save();
                }
            }
        }
    }
}
