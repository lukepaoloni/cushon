<?php

namespace App\Models;

use App\CustomerType;
use App\DataObjects\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fund_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}
