<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register()
    {
        Mail::fake();

        $response = $this->postJson('/api/auth/register', [
            'name' => 'Teste',
            'email' => 'teste@email.com',
            'password' => '123456'
        ]);

        $response->assertStatus(202);

        $response->assertJsonPath('token.header.content-type', 'application/json');
        $response->assertJsonPath('token.header.method', 'POST');
        $response->assertJsonPath('token.message', 'Token registrado com sucesso.');
        $response->assertJsonPath('token.status', 202);

        // Verifica que 'data' é true
        $this->assertTrue($response->json('token.data'));

        // Verifica que o token existe fora de 'data'
        $this->assertNotEmpty($response->json('token.token'));
    }

    public function test_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('123456')
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertStatus(200);

        $response->assertJsonPath('auth.header.content-type', 'application/json');
        $response->assertJsonPath('auth.header.method', 'POST');
        $response->assertJsonPath('auth.message', "Seja muito bem-vindo {$user->name}");
        $response->assertJsonPath('auth.status', 200);

        $this->assertArrayHasKey('token', $response->json('auth.data'));
    }

    public function test_me()
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/auth/me');

        $response->assertStatus(200);

        $response->assertJsonPath('auth.header.content-type', 'application/json');
        $response->assertJsonPath('auth.header.method', 'GET');
        $response->assertJsonPath('auth.status', 200);

        $data = $response->json('auth.data');

        $this->assertEquals($user->id, $data['id']);
        $this->assertEquals($user->name, $data['name']);
        $this->assertEquals($user->email, $data['email']);
    }
}
