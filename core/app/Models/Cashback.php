<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashback extends Model
{
    use HasFactory;

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function getStatusAttribute(): int
    {
        return $this->transaction->status;
    }

    public function getAmountAttribute(): int
    {
        return $this->transaction->amount;
    }
}
