<?php

namespace Tests\Feature\Retail;

use App\Models\Fund;
use Illuminate\Http\Response;
use Tests\TestCase;

class IsaInvestmentTest extends TestCase
{
    public function test_a_retail_customer_can_retrieve_available_funds()
    {
        // GIVEN I am an authenticated retail customer
        // (Authentication handled by middleware)
        // (Funds created by database seeds)

        // WHEN I send a GET request to "/api/retail/isa/funds"
        $response = $this->getJson('/api/retail/isa/funds');

        // THEN the response status code should be 200
        $response->assertStatus(Response::HTTP_OK);

        // AND the response should contain a list of available funds including "Cushon Equities Fund"
        $response->assertJsonFragment(['name' => 'Cushon Equities Fund']);
    }
}
