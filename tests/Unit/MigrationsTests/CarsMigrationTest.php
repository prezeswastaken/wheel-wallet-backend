<?php

namespace Tests\Unit;

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
            'model',
            'owner_id',
            'coowner_id',
            'status',
            'code',
            'created_at',
            'updated_at',
        ]));
    }

   
}