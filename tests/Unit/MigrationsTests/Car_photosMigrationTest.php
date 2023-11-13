<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Car_photosMigrationTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_car_photos_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('car_photos'));

        $this->assertTrue(Schema::hasColumns('car_photos', [
            'id',
            'car_id',
            'content',
            'created_at',
            'updated_at',
        ]));
    }

   
}