<?php

namespace App\Http\Controllers\Api\Retail;

use App\CustomerType;
use App\DataObjects\Money;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvestmentCollection;
use App\Http\Resources\InvestmentResource;
use App\Models\Fund;
use App\Models\Investment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = auth()->user()->investments()->with('fund')->latest()->paginate(15);
        return new InvestmentCollection($investments);
    }

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

        return response()->json(
            ['data' => new InvestmentResource($investment)],
            Response::HTTP_CREATED
        );
    }
}
