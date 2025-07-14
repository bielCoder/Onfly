<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_orders()
    {
        Order::factory()->count(3)->create();

        $response = $this->getJson('/api/order');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'travellings' => [
                'header' => ['content-type', 'method'],
                'data' => [
                    'current_page',
                    'data', // array de pedidos
                    'total',
                    'per_page',
                    'last_page'
                ],
                'status'
            ]
        ]);

        // Valida que existem 3 registros retornados
        $this->assertCount(3, $response->json('travellings.data.data'));

        // Verifica que o status é 200 no JSON
        $this->assertEquals(200, $response->json('travellings.status'));
    }

    public function test_change_status()
    {
        Mail::fake();

        $user = User::factory()->create();
        Order::factory()->create([
            'user_id' => $user->id,
            'travelling_id' => 1
        ]);

        $response = $this->putJson('/api/order/change', [
            'user_id' => $user->id,
            'travelling_id' => 1,
            'status' => 'aprovado'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'order' => [
                'header' => ['content-type', 'method'],
                'message',
                'status'
            ]
        ]);
    }

    public function test_clear_order()
    {
        Mail::fake();

        $user = User::factory()->create();
        Order::factory()->create([
            'user_id' => $user->id,
            'travelling_id' => 1,
            'status' => 'pendente'
        ]);

        $response = $this->deleteJson('/api/order/clear', [
            'user_id' => $user->id,
            'travelling_id' => 1,
            'status' => 'cancelado'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'order' => [
                'header' => ['content-type', 'method'],
                'message',
                'status'
            ]
        ]);
    }
}
