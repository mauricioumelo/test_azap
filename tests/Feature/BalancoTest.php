<?php

namespace Tests\Feature;

use Tests\TestCase;

class BalancoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

     public function test_example(): void
     {
         $response = $this->get('/api/v1/balanco');
         dd($response);
         $response->assertStatus(200);
     }
}
