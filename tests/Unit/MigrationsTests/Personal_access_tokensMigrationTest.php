<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class Personal_access_tokensMigrationTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_personal_access_tokens_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('personal_access_tokens'));

      
        $this->assertTrue(Schema::hasColumns('personal_access_tokens', [
            'id',
            'tokenable_type',
            'tokenable_id',
            'name',
            'token',
            'abilities',
            'last_used_at',
            'expires_at',
            'created_at',
            'updated_at',
        ]));
    }

   
}