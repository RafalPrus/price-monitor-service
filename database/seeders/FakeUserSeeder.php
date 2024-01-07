<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->raw([
            'email' => 'test@example.com',
            'password' => 'fakepass'
        ]);
        User::firstOrCreate(
            ['email' => $user['email']],
            $user,
        );
    }
}
