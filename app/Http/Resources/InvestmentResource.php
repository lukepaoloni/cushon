<?php

namespace App\Http\Resources;

use App\DataObjects\Money;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
{
    public function toArray($request)
    {
        $money = Money::fromPennies($this->amount);

        return [
            'id' => $this->id,
            'fund' => [
                'id' => $this->fund->id,
                'name' => $this->fund->name,
            ],
            'amount' => $money->getAmountInPounds(),
            'formatted_amount' => (string) $money,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'time_ago' => $this->created_at->diffForHumans(),
            'customer_type' => $this->customer_type,
        ];
    }
}
