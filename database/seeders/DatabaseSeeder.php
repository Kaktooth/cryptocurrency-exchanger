<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = \App\Models\User::factory(1)->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testuser',
        ]);

        $user->get(0)->first()->wallets()->createMany([
            [
                'short_name' => "EUR",
                'amount' => 15,
            ],
            [
                'short_name' => "USD",
                'amount' => 2,
            ]
        ]);

        $currency = \App\Models\Currency::factory(1)->createMany([
            [
                'success' => 'true',
                'timestamp' => '1519296206',
                'base' => 'EUR',
                'date' => "2021-03-17",
            ]
        ]);

        $currency->get(0)->first()->rates()->createMany([
            [
                'short_name' => "CAD",
                'value' => 1.44,
            ],
            [
                'short_name' => "USD",
                'value' => 1.13,
            ], 
            [
                'short_name' => "EUR",
                'value' => 1,
            ]
        ]);
    }
}