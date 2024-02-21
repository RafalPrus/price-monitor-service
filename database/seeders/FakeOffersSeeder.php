<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\PriceHistory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakeOffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offer = Offer::factory()->create([
            'user_id' => User::where('email', 'test@example.com')->first()->id,
        ]);

        PriceHistory::factory(10)->create([
            'offer_id' => $offer->id,
        ]);


    }
}
