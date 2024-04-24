<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'referrer_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }


    public function userTransaction()
    {
        return $this->hasOne(Transaction::class, 'user_id','user_id')
            ->where('type', 4);
    }


    public function referrerTransaction()
    {
        return $this->hasOne(Transaction::class, 'user_id', 'referrer_id')
            ->where('type', 3);
    }

    public function getStatusAttribute()
    {
        return $this->userTransaction->status;
    }

    public function scopeConfirmed()
    {
        return $this->whereHas('userTransaction', function ($transaction) {
            $transaction->where('status', 1);
        });
    }

    public function scopePending()
    {
        return $this->whereHas('userTransaction', function ($transaction) {
            $transaction->where('status', 0);
        });
    }

    public function scopeCancelled()
    {
        return $this->whereHas('userTransaction', function ($transaction) {
            $transaction->where('status', 2);
        });
    }


}
