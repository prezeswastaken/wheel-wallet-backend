<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DatabaseMigrationTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_users_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('users'));

      
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'name',
            'email',
            'password',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ]));
    }

   
}