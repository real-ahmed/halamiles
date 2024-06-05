<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptedCashoutMethod extends Model
{
    use HasFactory;
    protected $table='accepted_cashout_methods';
    protected $fillable = ['withdraw_method_id','transaction_id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function withdrawMethod()
    {
        return $this->belongsTo(WithdrawMethod::class);
    }
}
