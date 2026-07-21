<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        // Root path redirects to login
        $response = $this->get('/');
        $response->assertStatus(302);

        // Login screen returns 200
        $responseLogin = $this->get('/login');
        $responseLogin->assertStatus(200);
    }
}
