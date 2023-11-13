<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CarsMigrationTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_cars_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('cars'));

        $this->assertTrue(Schema::hasColumns('cars', [
            'id',
            'created_at',
            'updated_at',
        ]));
    }

   
}