<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('priorities')->insert([[
            'name' => 'high',
            'description' => 'High priority',
            'created_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
            'updated_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
        ],[
            'name' => 'medium',
            'description' => 'Medium priority',
            'created_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
            'updated_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
        ],
        [
            'name' => 'low',
            'description' => 'Low priority',
            'created_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
            'updated_at' => Carbon::now()->setTimezone('Asia/Jakarta'),
        ]]);
    }
}
