<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelTest extends TestCase
{
    // use RefreshDatabase;

    public function test_list_travellings()
    {
        $this->withoutExceptionHandling();
        /** @var \App\Models\User $user */
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'api');

        Travel::factory()->count(5)->create();

        $response = $this->getJson('/api/travellings');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'travellings' => [
                'header' => ['content-type', 'method'],
                'data' => [
                    'current_page',
                    'data'
                ],
                'status'
            ]
        ]);

        $this->assertGreaterThanOrEqual(5, count($response->json()['travellings']['data']['data']));
    }

    public function test_show_travel()
    {
        $this->withoutExceptionHandling();
        /** @var \App\Models\User $user */

        $user = \App\Models\User::factory()->create();

        $this->actingAs($user, 'api');

        $travel = Travel::factory()->create();

        $response = $this->getJson('/api/travellings/'.$travel->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'travellings' => [
                'header' => ['content-type', 'method'],
                'data' => [
                    'id',
                    'city',
                    'company',
                    'status'
                ],
                'status'
            ]
        ]);
    }

    public function test_destroy_travel()
    {
        $this->withoutExceptionHandling();
        /** @var \App\Models\User $user */

        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'api');

        $travel = Travel::factory()->create();

        $response = $this->deleteJson('/api/travellings/'.$travel->id);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'travellings' => [
                'header' => ['content-type', 'method'],
                'data',
                'message',
                'status'
            ]
        ]);

        $this->assertDatabaseHas('travellings', [
            'id' => $travel->id,
            'status' => false
        ]);
    }
}
