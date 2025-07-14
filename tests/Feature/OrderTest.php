<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderTest extends TestCase
{
    // use RefreshDatabase;

   public function test_list_orders()
    {
        $this->withoutExceptionHandling();

            /** @var \App\Models\User $user */
            $user = \App\Models\User::factory()->createOne();
            $this->actingAs($user, 'api');

        // Cria as orders relacionadas ao usuário autenticado
        Order::factory()->count(1)->createOne(['user_id' => $user->id]);

        $response = $this->getJson('/api/order');

        $response->assertStatus(200);

        $json = $response->json();

        $response->assertJsonStructure([
            'travellings' => [
                'header' => ['content-type', 'method'],
                'data' => [
                    'current_page',
                    'data',
                    'total',
                    'per_page',
                    'last_page'
                ],
                'status'
            ]
        ]);

        $this->assertEquals(200, $json['travellings']['status']);
        $this->assertCount(10, $json['travellings']['data']['data']);
    }



    public function test_change_status()
{
    Mail::fake();

    $this->withoutExceptionHandling();

    /** @var \App\Models\User $user */
    $user = User::factory()->create();

    $this->actingAs($user, 'api');

    /** @var \App\Models\Travel $travel */
    $travel = \App\Models\Travel::factory()->create();

    /** @var \App\Models\Order $order */
    $order = \App\Models\Order::factory()->create([
        'user_id' => $user->id,
        'travelling_id' => $travel->id,
        'status' => 'pendente'
    ]);

    // Requisição para alterar o status
    $response = $this->putJson('/api/order/change', [
        'user_id' => $user->id,
        'travelling_id' => $travel->id,
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

    // Verifica se o status foi alterado no banco
    $this->assertDatabaseHas('orders', [
        'user_id' => $user->id,
        'travelling_id' => $travel->id,
        'status' => 'aprovado'
    ]);

    // Verifica se o e-mail foi enviado
    Mail::assertSent(\App\Mail\NotifyGmail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
}

    public function test_clear_order()
    {
        Mail::fake();

        $this->withoutExceptionHandling();

        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        /** @var \App\Models\Travel $travel */
        $travel = \App\Models\Travel::factory()->create();

        /** @var \App\Models\Order $order */
        $order = \App\Models\Order::factory()->create([
            'user_id' => $user->id,
            'travelling_id' => $travel->id,
            'status' => 'pendente',
            'active' => true,
        ]);

        // Faz a requisição de cancelamento
        $response = $this->deleteJson('/api/order/clear', [
            'user_id' => $user->id,
            'travelling_id' => $travel->id,
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

        // Verifica se o pedido foi desativado no banco
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'travelling_id' => $travel->id,
            'active' => false
        ]);

        // Verifica se o e-mail de cancelamento foi enviado
        Mail::assertSent(\App\Mail\NotifyClearGmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }
}
