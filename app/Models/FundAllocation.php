<?php

namespace App\Models;

use App\DataObjects\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FundAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fund_id',
        'amount',
    ];

    public function investment(): BelongsTo
    {
        return $this->belongsTo(Investment::class);
    }

    public function fund(): BelongsTo
    {
        return $this->belongsTo(Fund::class);
    }
}
