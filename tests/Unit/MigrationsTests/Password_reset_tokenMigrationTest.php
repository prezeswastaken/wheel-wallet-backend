<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Password_reset_tokenMigrationTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_password_reset_tokens_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('password_reset_tokens'));

      
        $this->assertTrue(Schema::hasColumns('password_reset_tokens', [
            'email',
            'token',
            'created_at',
            
        ]));
    }

   
}