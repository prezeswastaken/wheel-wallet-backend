<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Log;
use App\Models\Car;
use App\Models\User;
use Tests\TestCase;

class LogControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeAllLogs(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $car = Car::factory()->create(['owner_id' => $user->id]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($admin)->get("/api/logs");

        $response->assertJsonStructure([
            'logs' => [
                [
                    'id',
                    'car_id',
                    'username',
                    'message',
                ],
            ],
        ]);
    }

    public function testUserCanNotSeeAllLogs(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($user)->get("/api/logs");

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanSeeLogsAboutHisCars(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($user)->get("/api/car/{$car->id}/logs");

        $response->assertJson([$log->toArray()]);
    }

    public function testUserCanSeeLogsAboutOtherUsersCarsAsCoowner(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create([
            'owner_id' => $user->id,
            'coowner_id' => $user2->id,
        ]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($user2)->get("/api/car/{$car->id}/logs");

        $response->assertJson([$log->toArray()]);
    }

    public function testUserCanNotSeeLogsAboutOtherUsersCars(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($user2)->get("/api/car/{$car->id}/logs");

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanSeeHisLogs(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($user)->get("/api/user/{$user->id}/logs");

        $response->assertJson([$log->toArray()]);
    }

    public function testUserCanNotSeeLogsAboutOtherUsers(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        $log = Log::factory()->create(['car_id' => $car->id]);

        $response = $this->actingAs($user2)->get("/api/user/{$user->id}/logs");

        $response->assertJson(['status' => 403]);
    }
}