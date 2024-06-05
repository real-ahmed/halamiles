<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawMethod extends Model
{
    use HasFactory;

    public function  modelWithdrawMethod()
    {
        return $this->hasMany(ModelWithdrawMethod::class);
    }





    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'accepted_cashout_methods');
    }



}
