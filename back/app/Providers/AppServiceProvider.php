<?php

namespace App\Providers;

use App\Services\Api\ScrapeopsService;
use App\Services\Contract\ApiProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApiProviderInterface::class, function ($app) {
            $model = config('theapp.api_provider');

            switch ($model) {
                case 'scrapeops':
                    return new ScrapeopsService();
                default:
                    throw new \InvalidArgumentException('Brak takiego dostawcy API');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
