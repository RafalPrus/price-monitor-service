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
use Illuminate\Support\Sleep;

class ProcessOfferScan implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        //Sleep::for(60)->seconds();

        $offerService = new OfferService();
        $offerService->processOffer($this->offer);
        if($offerService->success != true) {
            $this->fail();
        }
    }
}
