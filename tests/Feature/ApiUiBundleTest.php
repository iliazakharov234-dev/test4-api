<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiUiBundleTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_via_api_and_open_ui()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Bundle User',
            'email' => 'bundle@test.ru',
            'password' => 'Qwerty12',
            'password_confirmation' => 'Qwerty12',
        ]);
        $response->assertStatus(201);

        $ui = $this->get('/ui/register.html');
        $ui->assertStatus(200);
    }
}
