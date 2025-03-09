<?php

namespace App\Http\Controllers\Api\Retail;

use App\CustomerType;
use App\DataObjects\Money;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvestmentCollection;
use App\Http\Resources\InvestmentResource;
use App\Models\Fund;
use App\Models\Investment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = auth()->user()->investments()
            ->with('allocations.fund')
            ->where('customer_type', CustomerType::RETAIL)
            ->latest()
            ->paginate(15);

        return new InvestmentCollection($investments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fund_id' => 'required|exists:funds,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        try {
            return DB::transaction(function () use ($validated) {
                $money = Money::fromPounds($validated['amount']);
                $fund = Fund::lockForUpdate()->findOrFail($validated['fund_id']);

                $investment = new Investment([
                    'amount' => $money->getAmountInPennies(),
                    'customer_type' => CustomerType::RETAIL,
                ]);

                $investment->user()->associate(auth()->user());
                $investment->save();

                $investment->addAllocation($fund, $money);

                return response()->json(
                    ['data' => new InvestmentResource($investment)],
                    Response::HTTP_CREATED
                );
            }, 5);
        } catch (Exception $e) {
            Log::error('Investment creation failed', [
                'user_id' => auth()->id(),
                'fund_id' => $validated['fund_id'] ?? null,
                'amount' => $validated['amount'] ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(
                ['error' => 'Unable to process your investment at this time. Please try again later.'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
