<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrenciestableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $curency=[
            'USD',
            'AUD',
            'EUR',
            'JPY',
        ];
        foreach($curency as $money)
            Currency::create([
                'iso' => $money
            ]);
    }
}
