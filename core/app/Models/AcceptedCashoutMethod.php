<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptedCashoutMethod extends Model
{
    use HasFactory;
    protected $fillable = ['withdraw_method_id','transaction_id'];

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawalMethods()
    {
        return $this->hasMany(WithdrawMethod::class);
    }
}
