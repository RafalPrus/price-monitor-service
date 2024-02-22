<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $firstScanHour = rand(7, 9);
        $secondHourScan = rand(19, 21);
        $minutesScan = rand(1, 59);

        // test
        $firstScanHour = 12;
        $minutesScan = 43;
        $schedule->command('offers:scan')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
