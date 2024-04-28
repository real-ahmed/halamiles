<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Click;
use App\Models\Transaction;
use App\Models\ClickTransaction;

class admitad extends Command
{
    protected $signature = 'import:admitad {startDate? : Start date in Y-m-d format} {endDate? : End date in Y-m-d format}';
    protected $description = 'Import data from Admitad API';

    public function handle()
    {
        $startDate = $this->argument('startDate') ?: Carbon::now()->subDays(20)->format('Y-m-d');
        $endDate = $this->argument('endDate') ?: date('Y-m-d');

        $accessToken = $this->getAccessToken('BuX9X2gUmdXkHUkKFna10MxRBEFsHW', 'A5t3wHteMmIGJZUegIS94oM7HgziUz', 'statistics', 'halamiles', '{6baAHzF');
        if (!$accessToken) {
            $this->error('Could not retrieve access token');
            return;
        }

        $feedURL = "https://api.admitad.com/statistics/actions/?date_start={$startDate}&date_end={$endDate}&limit=200";

        $response = Http::withToken($accessToken)->get($feedURL);
        $responseData = $response->json();

        if (!empty($responseData['results'])) {
            var_dump($responseData['results']);
            $this->parseData($responseData['results']);
            $this->info('Data parsed and saved successfully');
        } else {
            $this->error('No data found');
        }
    }



    public function getAccessToken($clientId, $clientSecret, $scope, $username, $password)
    {
        $query = [
            'client_id' => $clientId,
            'grant_type' => 'client_credentials',
            'username' => $username,
            'password' => $password,
            'scope' => $scope
        ];

        $response = Http::asForm()
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret)
            ])
            ->post('https://api.admitad.com' . '/token/', $query);

        if ($response->successful()) {
            $this->accessToken = $response->json()['access_token'];
            return $this->accessToken;
        } else {
            throw new ApiException('Operation failed: ' . $response->body());
        }
    }




    private function parseData(array $data)
    {
        foreach ($data as $transaction) {
            $clickRef = (string) $transaction['subid'];
            $click = Click::where('id', $clickRef)->first();

            if ($click) {
                $cashback_type = $click->model->cashbacktype->id;
                $cashback = $click->model->cashback;
                $amount = 0;
                $status = 0;



                if ($cashback_type == 1 || $cashback_type == 3) {
                    $amount = (float) $cashback;
                } elseif ($cashback_type == 2) {
                    $orderAmount = (float) preg_replace('~[^0-9.,]~', '', $transaction['cart']);
                    $orderAmount = round($orderAmount, 2);
                    $amount = ((float) $click->model->user_percentage / 100) * $orderAmount;
                    $amount = convertCurrency($amount, $transaction['currency']);
                    $cashback_type = 1;
                }

                if ((string) $transaction['status'] === 'declined' || (string) $transaction['status'] === 'declined'  || (string) $transaction['status'] === 'deleted') {
                    $status = 2;
                } elseif ((string) $transaction['status'] === 'approved' || (string) $transaction['status'] === 'confirmed') {
                    $status = 1;
                }

                $siteTransaction = ClickTransaction::where('network_transaction_id', (int) $transaction['action_id'])
                    ->where('click_id', 1)
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
                    $clickTransaction->network_transaction_id = (int) $transaction['action_id'];
                    $clickTransaction->category_rate = $transaction['action'];

                    $clickTransaction->save();
                }
            }
        }
    }
}
