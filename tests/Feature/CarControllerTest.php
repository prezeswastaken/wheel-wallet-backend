<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Car;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function testAdminCanSeeAllCars(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $response = $this->actingAs($admin)->get("/api/car");

        $response->assertJsonStructure([
            'cars' => [
                [
                    'id',
                    'model',
                    'owner_id',
                    'coowner_id',
                    'status',
                    'code',
                ],
            ],
        ]);
    }

    public function testUserCanNotSeeAllCars(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create();

        $response = $this->actingAs($user)->get("/api/car");

        $response->assertJson(['status' => 403]);
    }
    
    public function testCarCanBeCreated(): void
    {
        $user = User::factory()->create();
        $car = [
            'model' => 'Opel Astra',
            'status' => 'Crashed',
        ];

        $log = [
            'username' => $user->name,
            'message' => "Car Opel Astra created",
        ];

        $this->actingAs($user)->post('/api/car', $car);

        $this->assertDatabaseHas('cars', $car);

        $this->assertDatabaseHas('logs', $log);
    }

    public function testCarCanNotBeCreatedInvalidData(): void
    {
        $user = User::factory()->create();
        $car = [
            'model' => Str::random(51),
            'status' => 'Crashed',
        ];

        $response = $this->actingAs($user)->post('/api/car', $car);

        $response->assertInvalid('model');
    }

    public function testCarCanNotBeCreatedUserNotAuthenticated(): void
    {
        $car = [
            'model' => 'Opel Astra',
            'status' => 'Crashed',
        ];

        $this->post('/api/car', $car);

        $this->assertDatabaseMissing('cars', $car);
    }

    public function testUserCanSeeHisCars(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $response = $this->actingAs($user)->get("/api/user/{$user->id}/cars");

        $response->assertJson([$car->toArray()]);
    }

    public function testUserCanSeeCarsOfOtherUserAsCoowner(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create([
            'owner_id' => $user->id,
            'coowner_id' => $user2->id,
        ]);

        $response = $this->actingAs($user2)->get("/api/user/{$user2->id}/cars");

        $response->assertJson([$car->toArray()]);
    }

    public function testUserCanNotSeeOtherUsersCars(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $response = $this->actingAs($user2)->get("/api/user/{$user->id}/cars");

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanEditHisCars(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create([
            'model' => 'Opel Astra',
            'status' => 'Factory new',
            'owner_id' => $user->id,
        ]);
        $data = [
            'model' => 'Opel',
            'status' => 'temporarily out of order',
        ];

        $log = [
            'car_id' => $car->id,
            'username' => $user->name,
            'message' => "Car edited",
        ];

        $this->actingAs($user)->put("/api/car/{$car->id}/edit", $data)->assertJson(['status' => 200]);
        $car->refresh();

        $this->assertDatabaseHas('cars', $car->toArray());

        $this->assertDatabaseHas('logs', $log);
    }

    public function testUserCanNotEditOtherUsersCars(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create([
            'model' => 'Opel Astra',
            'status' => 'Factory new',
            'owner_id' => $user->id,
        ]);
        $data = [
            'model' => 'Opel',
            'status' => 'temporarily out of order',
        ];

        $response = $this->actingAs($user2)->put("/api/car/{$car->id}/edit", $data);
        $response->assertJson(['status' => 403]);
    }

    public function testUserCanNotEditHisCarsInvalidData(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create([
            'model' => 'Opel Astra',
            'status' => 'Factory new',
            'owner_id' => $user->id,
        ]);
        $data = [
            'model' => Str::random(51),
            'status' => 'temporarily out of order',
        ];

        $response = $this->actingAs($user)->put("/api/car/{$car->id}/edit", $data);

        $response->assertInvalid('model');
    }

    public function testCarCanBeDeleted(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $this->actingAs($user)->delete("/api/car/{$car->id}/delete");

        $this->assertDatabaseMissing('cars', $car->toArray());
    }

    public function testUserCanNotDeleteCarsOfOtherUsers(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $response = $this->actingAs($user2)->delete("/api/car/{$car->id}/delete");

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanJoinAsCoowner(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $data = ['code' => $car->code];

        $log = [
            'car_id' => $car->id,
            'username' => $user2->name,
            'message' => "{$user2->name} joined as co-owner",
        ];

        $this->actingAs($user2)->post("/api/car/join", $data)->assertStatus(200);
        $car->refresh();

        $response = $this->actingAs($user2)->get("/api/user/{$user2->id}/cars");
        
        $response->assertJson([$car->toArray()]);

        $this->assertDatabaseHas('logs', $log);
    }

    public function testUserCanNotJoinAsCoownerWrongCode(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $data = ['code' => '6githabsat312v'];

        $this->actingAs($user2)->post("/api/car/join", $data)->assertStatus(404);
    }
}
