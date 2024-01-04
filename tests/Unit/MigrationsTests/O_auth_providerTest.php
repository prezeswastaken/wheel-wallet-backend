<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class O_auth_providerTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_o_auth_providers_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('o_auth_providers'));

        $this->assertTrue(Schema::hasColumns('o_auth_providers', [
            'id',
            'provider',
            'provider_id',
            'created_at',
            'updated_at',
            'user_id'
        ]));
    }

   
}