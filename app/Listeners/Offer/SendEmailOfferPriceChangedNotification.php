<?php

namespace App\Listeners\Offer;

use App\Notifications\Offer\OfferPriceChangedNotification;
use App\Events\Offer\OfferPriceChanged;
use App\Models\User;
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
        $user = User::find($event->offer->user_id);
        Notification::send($user, new OfferPriceChangedNotification($event->offer));
    }
}
