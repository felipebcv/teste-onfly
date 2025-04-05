<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TravelOrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('travel_order_statuses')->insert([
            [
                'name'       => 'requested',
                'description'=> 'Travel order has been requested',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'approved',
                'description'=> 'Travel order has been approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'cancelled',
                'description'=> 'Travel order has been cancelled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
