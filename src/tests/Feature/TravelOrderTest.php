<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Destination;
use App\Models\TravelOrderStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_travel_order()
    {
        // Arrange
        $user = User::factory()->create();

        $destination = Destination::create([
            'name' => 'São Paulo',
            'description' => 'Destination in Brazil'
        ]);

        TravelOrderStatus::create([
            'name' => 'requested',
            'description' => 'Order requested'
        ]);

        $this->actingAs($user, 'sanctum');

        // Act
        $response = $this->postJson('/api/travel-orders', [
            'destination_id' => $destination->id,
            'departure_date' => '2025-05-01',
            'return_date'    => '2025-05-10'
        ]);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'destination_id',
                'departure_date',
                'return_date',
                'status_id',
                'created_at',
                'updated_at'
            ]);

        $this->assertDatabaseHas('travel_orders', [
            'user_id' => $user->id,
            'destination_id' => $destination->id,
            'status_id' => 1
        ]);
    }
}
