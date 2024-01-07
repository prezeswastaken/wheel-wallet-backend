<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Car;
use App\Models\Expense;
use Tests\TestCase;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testExpenseCanBeCreated(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = [
            'car_id' => $car->id,
            'name' => 'Refueling',
            'cost' => 78.96,
            'date' => '2024-01-05',
            'planned' => false,
        ];

        $log = [
            'car_id' => $car->id,
            'username' => $user->name,
            'message' => "Expense Refueling created",
        ];

        $this->actingAs($user)->post('/api/expense', $expense);

        $this->assertDatabaseHas('expenses', [
            'car_id' => $car->id,
            'name' => 'Refueling',
            'cost' => 78.96*100,
            'date' => '2024-01-05',
            'planned' => false,
        ]);

        $this->assertDatabaseHas('logs', $log);
    }

    public function testUserCanCreateExpenseForOtherUsersCarsAsCoowner(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create([
            'owner_id' => $user->id,
            'coowner_id' => $user2->id,
        ]);

        $expense = [
            'car_id' => $car->id,
            'name' => 'Refueling',
            'cost' => 78.96,
            'date' => '2024-01-05',
            'planned' => false,
        ];

        $log = [
            'car_id' => $car->id,
            'username' => $user2->name,
            'message' => "Expense Refueling created",
        ];

        $this->actingAs($user2)->post('/api/expense', $expense);

        $this->assertDatabaseHas('expenses', [
            'car_id' => $car->id,
            'name' => 'Refueling',
            'cost' => 78.96*100,
            'date' => '2024-01-05',
            'planned' => false,
        ]);

        $this->assertDatabaseHas('logs', $log);
    }

    public function testExpenseCanNotBeCreatedInvalidData(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = [
            'car_id' => $car->id,
            'name' => 'Refueling',
            'cost' => 'Invalid data',
            'date' => '2024-01-05',
            'planned' => false,
        ];

        $response = $this->actingAs($user)->post('/api/expense', $expense);

        $response->assertInvalid('cost');
    }

    public function testUserCanNotCreateExpenseForOtherUsersCars(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = [
            'car_id' => $car->id,
            'name' => 'Refueling',
            'cost' => 78.96,
            'date' => '2024-01-05',
            'planned' => false,
        ];

        $response = $this->actingAs($user2)->post('/api/expense', $expense);

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanEditHisExpenses(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'name' => 'Refueling',
        ]);

        $data = [
            'name' => 'Wheel change',
            'date' => $expense->date,
            'planned' => $expense->planned,
        ];

        $log = [
            'car_id' => $car->id,
            'username' => $user->name,
            'message' => "Expense Wheel change updated",
        ];

        $this->actingAs($user)->put("/api/expense/{$expense->id}/edit", $data)->assertJson(['status' => 200]);
        $expense->refresh();

        $this->assertDatabaseHas('expenses', $expense->toArray());

        $this->assertDatabaseHas('logs', $log);
    }

    public function testUserCanNotEditOtherUsersExpenses(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'name' => 'Refueling',
        ]);

        $data = [
            'name' => 'Wheel change',
            'date' => $expense->date,
            'planned' => $expense->planned,
        ];

        $response = $this->actingAs($user2)->put("/api/expense/{$expense->id}/edit", $data);

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanNotEditHisExpensesInvalidData(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);
        
        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
            'name' => 'Refueling',
        ]);

        $data = [
            'name' => 'Wheel change',
            'cost' => 'Invalid data',
            'date' => $expense->date,
            'planned' => $expense->planned,
        ];

        $response = $this->actingAs($user)->put("/api/expense/{$expense->id}/edit", $data);

        $response->assertInvalid('cost');
    }

    public function testExpenseCanBeDeleted(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);

        $this->actingAs($user)->delete("/api/expense/{$expense->id}/delete");

        $this->assertDatabaseMissing('expenses', $expense->toArray());
    }

    public function testUserCanNotDeleteExpensesOfOtherUsers(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);

        $response = $this->actingAs($user2)->delete("/api/expense/{$expense->id}/delete");

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanSeeHisExpenses(): void
    {
        $user = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);

        $response = $this->actingAs($user)->get("/api/user/{$user->id}/expenses");

        $response->assertJson([$expense->toArray()]);
    }

    public function testUserCanNotSeeOtherUsersExpenses(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create(['owner_id' => $user->id]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);

        $response = $this->actingAs($user2)->get("/api/user/{$user->id}/expenses");

        $response->assertJson(['status' => 403]);
    }

    public function testUserCanSeeExpensesOfHisCarsAsCoowner(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $car = Car::factory()->create([
            'owner_id' => $user->id,
            'coowner_id' => $user2->id,
        ]);

        $expense = Expense::factory()->create([
            'user_id' => $user->id,
            'car_id' => $car->id,
        ]);

        $response = $this->actingAs($user2)->get("/api/car/{$car->id}/expenses");

        $response->assertJson([$expense->toArray()]);
    }
}
