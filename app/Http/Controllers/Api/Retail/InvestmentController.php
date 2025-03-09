<?php

namespace App\Http\Controllers\Api\Retail;

use App\CustomerType;
use App\DataObjects\Money;
use App\Http\Controllers\Controller;
use App\Models\Fund;
use App\Models\Investment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fund_id' => 'required|exists:funds,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $money = Money::fromPounds($validated['amount']);
        $fund = Fund::findOrFail($validated['fund_id']);

        $investment = new Investment([
            'amount' => $money->getAmountInPennies(),
            'customer_type' => CustomerType::RETAIL,
        ]);

        $investment->user()->associate(auth()->user());
        $investment->fund()->associate($fund);
        $investment->save();

        return response()->json(['data' =>
            [
                'id' => $investment->id,
                'user_id' => $investment->user_id,
                'fund_id' => $investment->fund_id,
                'amount' => $money->getAmountInPounds(),
                'formatted_amount' => (string) $money,
                'customer_type' => $investment->customer_type,
                'created_at' => $investment->created_at,
                'updated_at' => $investment->updated_at
            ]
        ], Response::HTTP_CREATED);
    }
}
