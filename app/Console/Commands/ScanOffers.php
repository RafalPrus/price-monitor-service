<?php

namespace App\Console\Commands;

use App\Events\Offer\OfferPriceChanged;
use App\Jobs\ProcessOfferScan;
use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use App\Notifications\OfferPriceChangedNotification;
use Illuminate\Console\Command;

class ScanOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'td';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
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
