<?php

namespace App\Models;

use App\CustomerType;
use App\DataObjects\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'customer_type',
    ];

    public function getMoneyAttribute(): Money
    {
        return Money::fromPennies($this->amount);
    }

    public function scopeRetail($query)
    {
        return $query->where('customer_type', CustomerType::RETAIL);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * This relationship will be used in the future when we support multiple fund selections.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(FundAllocation::class);
    }

    /**
     * In the current implementation, there will only be one allocation per investment,
     * but this method prepares for future support of multiple allocations.
     */
    public function addAllocation(Fund $fund, Money $amount): FundAllocation
    {
        return $this->allocations()->create([
            'fund_id' => $fund->id,
            'amount' => $amount->getAmountInPennies(),
        ]);
    }
}
