<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;



    public function transaction()
    {

        return $this->belongsTo(Transaction::class);

    }
    
    public function method()
    {
        
        return $this->belongsTo(WithdrawMethod::class);
        
    }
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
        
    }



    
    public function getAmountAttribute()
    {
        return $this->transaction->amount * $this->method->rate;
        
    }
    public function getChargeAttribute()
    {
        return $this->transaction->charge * $this->method->rate;
        
    }

    public function getFinalAmountAttribute()
    {
        return $this->amount - $this->charge;
        
    }
    
    public function scopePending($query)
    {
        return $query->whereHas('transaction', function ($q) {
            $q->where('status', 0);
        });
    }
    public function scopeApproved($query)

    {

        return $query->whereHas('transaction', function ($q) {
            $q->where('status', 1);
        });

    }


    public function scopeRejected($query)

    {

        return $query->whereHas('transaction', function ($q) {
            $q->where('status', 2);
        });

    }


    
}
