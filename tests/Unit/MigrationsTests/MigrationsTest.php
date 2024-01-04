<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationsTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_cars_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('migrations'));

        $this->assertTrue(Schema::hasColumns('migrations', [
            'id',
            'migration',
            'batch'
        ]));
    }

   
}