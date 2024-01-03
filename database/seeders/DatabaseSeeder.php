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
        \App\Models\User::factory()->create();
        \App\Models\Car::factory()->create();
        \App\Models\Log::factory()->create();
        \App\Models\Expense::factory()->create();
        \App\Models\CarPhoto::factory()->create();
    }
}
