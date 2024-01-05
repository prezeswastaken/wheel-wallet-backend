<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationsTest extends TestCase
{
    use RefreshDatabase;

   
    public function test_migrations_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('migrations'));

        $this->assertTrue(Schema::hasColumns('migrations', [
            'id',
            'migration',
            'batch'
        ]));
    }
     
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
    public function test_failed_jobs_table_has_expected_columns()
    {
       
        $this->artisan('migrate');

        $this->assertTrue(Schema::hasTable('failed_jobs'));

        $this->assertTrue(Schema::hasColumns('failed_jobs', [
            'id',
            'uuid',
            'connection',
            'queue',
            'payload',
            'exception',
            'failed_at',
        ]));
    }
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
            'profile_picture',
            'is_admin',
            'remember_token',
            'created_at',
            'updated_at',
            'google_id',
            
        ]));
    }
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