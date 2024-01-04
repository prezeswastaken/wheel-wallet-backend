<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeAllUsers(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get("/api/users");

        $response->assertJsonStructure([
            'users' => [
                [
                    'id',
                    'name',
                    'email',
                    'is_admin',
                ],
            ],
        ]);
    }

    public function testUserCanNotSeeAllUsers(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($user)->get("/api/users");

        $response->assertJson(['status' => 403]);
    }

    public function testAdminCanDeleteUser(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->delete("/api/user/{$user->id}/delete")->assertJson(['status' => 200]);

        $this->assertDatabaseMissing('users', $user->toArray());
    }

    public function testUserCanNotDeleteOtherUsers(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user)->delete("/api/user/{$user2->id}/delete");

        $response->assertJson(['status' => 403]);
    }
}
