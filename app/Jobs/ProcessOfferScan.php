<?php

namespace App\Jobs;

use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Sleep;
use Throwable;

class ProcessOfferScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 30;
    public $timeout = 140;
    public $failOnTimeout = true;
    /**
     * Create a new job instance.
     */
    public function __construct(
        public Offer $offer,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $offerService = new OfferService();
        $offerService->processOffer($this->offer);
        if($offerService->success != true) {
            $this->fail('Invalid body request');
        }
    }
}
