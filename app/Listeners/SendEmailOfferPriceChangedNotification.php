<?php

namespace App\Listeners;

use App\Notifications\OfferPriceChangedNotification;
use App\Events\OfferPriceChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendEmailOfferPriceChangedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OfferPriceChanged $event): void
    {
        dump('w listener');
        Notification::send(auth()->user(), new OfferPriceChangedNotification($event->offer));
    }
}
