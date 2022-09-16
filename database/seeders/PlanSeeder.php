<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Osiset\ShopifyApp\Storage\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $plan = new Plan();
        $plan->name = "Basic";
        $plan->type = "RECURRING";
        $plan->price = 4.99;
        $plan->capped_amount = 0.00;
        $plan->terms = "Demo terms";
        $plan->trial_days = 0;
        $plan->save();

        $plan2 = new Plan();
        $plan2->name = "Standard";
        $plan2->type = 'RECURRING';
        $plan2->price = 12.99;
        $plan2->capped_amount = 0.00;
        $plan2->terms = "Demo terms";
        $plan2->trial_days = 0;
        $plan2->save();

        $plan3 = new Plan();
        $plan3->name = "Premium";
        $plan3->type = 'RECURRING';
        $plan3->price = 24.99;
        $plan3->capped_amount = 0.00;
        $plan3->terms = "Demo terms";
        $plan3->trial_days = 0;
        $plan3->save();
        
    }
}
