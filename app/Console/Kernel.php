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
        $secondScanHour = rand(19, 21);
        $scanMinutes = rand(1, 59);

        $schedule->command('offers:scan')->twiceDailyAt($firstScanHour, $secondScanHour, $scanMinutes);
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
