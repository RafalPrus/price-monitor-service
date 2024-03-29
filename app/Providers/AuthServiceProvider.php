<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Offer;
use App\Policies\OfferPolicy;
use App\Services\FrontUrlService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Offer::class => OfferPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // generowanie linka weryfikacji przez front
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $parsed = parse_url($url);
            $dynamicPathParts = array_slice(explode('/', $parsed['path']), -2, 2);
            $frontUrl = (new FrontUrlService())->getConfirmEmailUrl();
            $params = [
                'id' => $dynamicPathParts[0],
                'token' => $dynamicPathParts[1],
            ];

            $queryPath = http_build_query($params);
            $preparedUrl = $frontUrl . '?' . $queryPath . '&' . $parsed['query'];
            return (new MailMessage)
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $preparedUrl);
        });
    }
}
