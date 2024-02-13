<?php

namespace App\Console\Commands;

use App\Events\OfferPriceChanged;
use App\Jobs\ProcessOfferScan;
use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use App\Notifications\OfferPriceChangedNotification;
use Illuminate\Console\Command;

class TestScanOffers extends Command
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
    public function handle()
    {

        $user = User::factory()->create();
        auth()->login($user);
        //$this->actingAs($user);
        $offersCount = 4;
        $offers = Offer::factory()
            ->count($offersCount)
            ->has(PriceHistory::factory()->count(5), 'priceHistories')
            ->create([
                'user_id' => $user->id,
            ]);

        foreach ($offers as $offer) {
            $offer->price_history_actual_id = $offer->priceHistories->first()->id;
            $offer->save();
        }
        
        foreach ($offers as $offer) {
            // dump($user->email);
            // $user->notify(new OfferPriceChangedNotification($offer));
            dump('before');
            OfferPriceChanged::dispatch($offer);
            dump('after');
            //Notification::send(auth()->user(), new OfferPriceChangedNotification($offer));
        }
        // $activeUsers = User::where('is_active', 1);
        // foreach($activeUsers as $user) {
        //     $activeOffers = $user->offers()->where('is_active', 1);
        //     foreach ($activeOffers as $offer) {
        //         ProcessOfferScan::dispatch($offer);
        //     }
        // }
    }
}
