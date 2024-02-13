<?php

namespace App\Console\Commands;

use App\Jobs\ProcessOfferScan;
use App\Models\Offer;
use App\Models\User;
use Illuminate\Console\Command;

class ScanOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scan-offers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $activeUsers = User::where('is_active', 1);
        foreach($activeUsers as $user) {
            $activeOffers = $user->offers()->where('is_active', 1);
            foreach ($activeOffers as $offer) {
                ProcessOfferScan::dispatch($offer);
            }
        }
    }
}
