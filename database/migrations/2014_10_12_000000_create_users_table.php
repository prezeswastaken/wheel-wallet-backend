<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->binary('profile_picture')->nullable();
            $table->boolean('is_admin')->default('false');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                'name' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make("admin123"),
                'is_admin' => true
            )
        );
        DB::table('users')->insert(
            array(
                'name' => 'user',
                'email' => 'user@example.com',
                'password' => Hash::make("test123"),
                'is_admin' => false
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
