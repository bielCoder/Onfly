<?php

namespace Tests\Feature;

use App\Models\Travel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TravelTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_travellings()
    {
        Travel::factory()->create();

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
    }

    public function test_show_travel()
    {
        $travel = Travel::factory()->create();

        $response = $this->getJson('/api/travellings/'.$travel->id);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'travellings' => [
                'header' => ['content-type', 'method'],
                'data' => [
                    'id',
                    'name',
                    'status'
                ],
                'status'
            ]
        ]);
    }

    public function test_destroy_travel()
    {
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
    }
}