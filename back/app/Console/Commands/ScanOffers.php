<?php

namespace App\Console\Commands;

use App\Events\Offer\OfferPriceChanged;
use App\Jobs\ProcessOfferScan;
use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use App\Notifications\OfferPriceChangedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScanOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'offers:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run scan offer process';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $activeUsers = User::whereNotNull('email_verified_at')->get();
        foreach($activeUsers as $user) {
            $activeOffers = $user->offers()->where('is_active', 1)->get();
            foreach ($activeOffers as $offer) {
                ProcessOfferScan::dispatch($offer);
            }
        }
    }
}
