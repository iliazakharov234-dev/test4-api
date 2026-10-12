<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ivan',
            'email' => 'ivan@test.ru',
            'password' => 'Qwerty12',
            'password_confirmation' => 'Qwerty12',
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
    }

    public function test_register_invalid_email()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Ivan',
            'email' => 'invalid-email',
            'password' => 'Qwerty12',
            'password_confirmation' => 'Qwerty12',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_register_duplicate_email()
    {
        User::factory()->create(['email' => 'dup@test.ru']);
        $response = $this->postJson('/api/register', [
            'name' => 'Petr',
            'email' => 'dup@test.ru',
            'password' => 'Qwerty12',
            'password_confirmation' => 'Qwerty12',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_login_success()
    {
        User::factory()->create([
            'email' => 'login@test.ru',
            'password' => bcrypt('Qwerty12'),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'login@test.ru',
            'password' => 'Qwerty12',
        ]);
        $response->assertStatus(200)->assertJsonStructure(['token']);
    }

    public function test_login_wrong_password()
    {
        User::factory()->create([
            'email' => 'wrong@test.ru',
            'password' => bcrypt('Qwerty12'),
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'wrong@test.ru',
            'password' => 'WrongPass1',
        ]);
        $response->assertStatus(401);
    }

    public function test_profile_without_token()
    {
        $response = $this->getJson('/api/profile');
        $response->assertStatus(401);
    }

    public function test_profile_with_invalid_token()
    {
        $response = $this->withHeader('Authorization', 'Bearer invalid_token')
                         ->getJson('/api/profile');
        $response->assertStatus(401);
    }

    public function test_register_empty_body()
    {
        $response = $this->postJson('/api/register', []);
        $response->assertStatus(422);
    }

    public function test_response_schema()
    {
        User::factory()->create([
            'email' => 'schema@test.ru',
            'password' => bcrypt('Qwerty12'),
        ]);
        $login = $this->postJson('/api/login', [
            'email' => 'schema@test.ru',
            'password' => 'Qwerty12',
        ]);
        $token = $login->json('token');
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
                         ->getJson('/api/profile');
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => ['id', 'name', 'email', 'created_at']]);
    }

    public function test_nonexistent_endpoint()
    {
        $response = $this->getJson('/api/nonexistent');
        $response->assertStatus(404);
    }

    public function test_password_7_chars()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Boundary',
            'email' => 'boundary7@test.ru',
            'password' => 'Abc1234',
            'password_confirmation' => 'Abc1234',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_password_8_chars()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Boundary',
            'email' => 'boundary8@test.ru',
            'password' => 'Abc12345',
            'password_confirmation' => 'Abc12345',
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
    }

    public function test_password_64_chars()
    {
        $password = str_repeat('A', 63) . '1';
        $response = $this->postJson('/api/register', [
            'name' => 'Boundary',
            'email' => 'boundary64@test.ru',
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
    }

    public function test_password_65_chars()
    {
        $password = str_repeat('A', 64) . '1';
        $response = $this->postJson('/api/register', [
            'name' => 'Boundary',
            'email' => 'boundary65@test.ru',
            'password' => $password,
            'password_confirmation' => $password,
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['password']);
    }

    public function test_name_255_chars()
    {
        $response = $this->postJson('/api/register', [
            'name' => str_repeat('A', 255),
            'email' => 'boundaryname255@test.ru',
            'password' => 'Qwerty12',
            'password_confirmation' => 'Qwerty12',
        ]);
        $response->assertStatus(201)
                 ->assertJsonStructure(['user' => ['id', 'name', 'email'], 'token']);
    }

    public function test_name_256_chars()
    {
        $response = $this->postJson('/api/register', [
            'name' => str_repeat('A', 256),
            'email' => 'boundaryname256@test.ru',
            'password' => 'Qwerty12',
            'password_confirmation' => 'Qwerty12',
        ]);
        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }
}
