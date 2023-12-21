<?php

use Illuminate\Database\Seeder;
use App\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plans = [
            [
                'name' => 'Suscripción mensual PRO',
                'slug' => 'plan-pro',
                'stripe_plan' => 'price_1MMYBiCbKz5YJFE38v1Luw9H',
                'price' => 6,
                'tax' => '0',
                'type_plan' => '1',
                'description' => 'Suscripción mensual PRO'

            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
