<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashback()
    {
        return $this->belongsTo(Cashback::class);
    }

    public function note()
    {
        return $this->morphOne(Note::class, 'model');
    }




    public function clickTransaction()
    {
        return $this->hasOne(ClickTransaction::class);
    }


    public function withdrawal()
    {
        return $this->hasOne(Withdrawlal::class);
    }


    public function scopeConfirmed()
    {
        return $this->where('status', 1);

    }

    public function scopePending()
    {
        return $this->where('status', 0);

    }

    public function scopeCancelled()
    {
        return $this->where('status', 2);

    }


    public function getTypeAttribute($value)
    {
        $returnTypes = [
            0 => 'credit',
            1 => 'gift',
            3 => 'referrer',
            4 => 'referral',
            5 => 'cashback',
            6 => 'withdrawal',
            7 => 'banned withdrawal',
        ];

        return $returnTypes[$value] ?? 'unknown';
    }



    public function getTitleAttribute()
    {
        $type = $this->type;
    
        switch ($type) {
            case 'credit':
                return 'Credit Transaction';
            case 'gift':
                return 'Gift';
            case 'referrer':
                return 'Invite a friend';
            case 'referral':
                return 'Invited by friend';
            case 'withdrawal':
                return 'Withdrawal';
            case 'banned withdrawal':
                return 'Banned Withdrawal';

            case 'cashback':
                return optional($this->clickTransaction)->click->model->title ?? 'No associated click transaction';
            default:
                return 'Unknown Transaction';
        }
    }

    public function getCategoryAttribute()
    {
        $type = $this->type;
    
        switch ($type) {
            case 'credit':
                return 'Credit';
            case 'gift':
                return 'Gift';
            case 'referrer':
                return 'Referrals';
            case 'referral':
                return 'Referrals';
            case 'withdrawal':
                return 'Withdrawal';
            case 'banned withdrawal':
                return 'Banned Withdrawal';

            case 'cashback':
                return optional($this->clickTransaction)->click->model->category->name ?? 'No associated click transaction';
            default:
                return 'Transaction';
        }
    }


}
