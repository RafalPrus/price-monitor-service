<?php

namespace App\Notifications\Offer;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfferPriceChangedNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public Offer $offer;
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->greeting("Oferta {$this->offer->name} zmeniła się")
                    ->line("Nowa cena: {$this->offer->price_current}")
                    ->action('Przejdź do oferty', url($this->offer->url))
                    ->line('Dzięki, że jesteś z nami!');
    }
}
