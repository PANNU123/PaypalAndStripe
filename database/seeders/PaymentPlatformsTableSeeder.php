<?php

namespace Database\Seeders;

use App\Models\PaymentPlatForm;
use Illuminate\Database\Seeder;

class PaymentPlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentPlatForm::create([
            'name'  =>  'Paypal',
            'image' =>  'img/paypal.png'
        ]);
        PaymentPlatForm::create([
            'name'  =>  'Stripe',
            'image' =>  'img/stripe.png'
        ]);
    }
}
