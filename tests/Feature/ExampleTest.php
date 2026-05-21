<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PortalSeeder::class);
    }
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
