<?php

namespace Tests\Feature\Retail;

use App\DataObjects\Money;
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

    public function test_a_retail_customer_can_create_a_new_investment()
    {
        // GIVEN I have selected "Cushon Equities Fund" as a fund I'd like to invest in
        // (Authentication handled by middleware)
        $fund = Fund::query()->where('name', 'Cushon Equities Fund')->firstOrFail();

        // WHEN I send a POST request to "/api/retail/isa/investments" with fund and amount
        $response = $this->postJson('/api/retail/isa/investments', [
                'fund_id' => $fund->id,
                'amount' => 25000
            ]);

        // THEN the response status code should be 201
        $response->assertStatus(Response::HTTP_CREATED);

        // AND the response should include the investment details
        $response->assertJsonFragment([
            'fund_id' => $fund->id,
            'amount' => 25000
        ]);

        // AND the investment should be stored in the database
        $this->assertDatabaseHas('investments', [
            'user_id' => auth()->id(),
            'fund_id' => $fund->id,
            'amount' => Money::fromPounds(25000)->getAmountInPennies(),
        ]);
    }
}
