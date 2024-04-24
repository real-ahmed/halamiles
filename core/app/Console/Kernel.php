<?php



namespace App\Console;



use Illuminate\Console\Scheduling\Schedule;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Models\GeneralSetting;

class Kernel extends ConsoleKernel

{

    /**

     * Define the application's command schedule.

     *

     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule

     * @return void

     */

    protected function schedule(Schedule $schedule)

    {
  // Fetch the general settings from the database
    $general = GeneralSetting::first();

    // If the transaction_update attribute is valid, schedule the deposits check
    if (is_numeric($general->transaction_update)) {
        $schedule->command('deposits:check-status')->hourlyAt($general->transaction_update);
    } else {
        // If not, you may want to log an error or handle it in some way
        \Log::error('Invalid transaction_update value in GeneralSetting');
    }

    // Schedule the currency rates update to run daily
    $schedule->command('update:currency-rates')->daily();

    // Schedule import commands to run every ten minutes
    $schedule->command('import:cj')->everyTenMinutes();
    $schedule->command('import:awin')->everyTenMinutes();
    $schedule->command('import:admitad')->everyTenMinutes();
    $schedule->command('import:hasoffers arabclicks 79352f4855e036f0634108b4e079d70d143905e71466c735dff24dddc1178909')->everyTenMinutes();
    $schedule->command('import:hasoffers vcm 08cf360d81fcea79b9fbd5f149cec3fb7606357a659d779c3045842f66e8e4a5')->everyTenMinutes();
    $schedule->command('import:hasoffers dcm 7cf26c37-1588-465a-a879-61cb236c1ae8')->everyTenMinutes();

        







    }



    /**

     * Register the commands for the application.

     *

     * @return void

     */

    protected function commands()

    {

        $this->load(__DIR__.'/Commands');



        require base_path('routes/console.php');

    }

}

