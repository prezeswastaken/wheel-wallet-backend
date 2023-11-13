<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExpensesMigrationTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_expenses_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('expenses'));

      
        $this->assertTrue(Schema::hasColumns('expenses', [
            'id',
            'user_id',
            'car_id',
            'name',
            'cost',
            'date',
            'planned',
            'created_at',
            'updated_at',
        ]));
    }

   
}