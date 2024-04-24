<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:currency-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the currency rates from API';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = "https://api.currencyapi.com/v3/latest?apikey=cur_live_YsCLON6nrHeRrvpEGYF1xdNgIMTRUDJd9kZgOiq8";
        $response = Http::get($url);
    
        if($response->successful()) {
            $currencies = $response->json();
    
            // Define a path to the file
            $filePath = storage_path('app/currencies.json');
    
            // Write the file
            file_put_contents($filePath, json_encode($currencies, JSON_PRETTY_PRINT));
    
            $this->info('Currency rates updated successfully!');
        }
    }
    
}
