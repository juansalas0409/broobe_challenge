<?php

namespace Database\Seeders;

use App\Models\Strategy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrategySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('strategies')->truncate();
        Strategy::insert([
            ['name' => 'DESKTOP'],
            ['name' => 'MOBILE'],
        ]);

    }
}
