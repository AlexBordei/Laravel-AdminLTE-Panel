<?php

namespace Database\Seeders;

use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $subscription = new Subscription();
            $subscription->student_id = 2;
            $subscription->subscription_type_id = 3;
            $subscription->starting = Carbon::now();
            $subscription->ending = Carbon::tomorrow();
            $subscription->save();
    }
}

