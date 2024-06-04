<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelWithdrawMethod extends Model
{
    use HasFactory;
    public function model()
    {
        return $this->morphTo();
    }

    public function withdrawMethod(){
        return $this->belongsTo(WithdrawMethod::class);
    }


    public static function activeWithdrawMethodNames($modelWithdrawMethods)
    {
        return $modelWithdrawMethods->filter(function ($modelWithdrawMethod) {
            return $modelWithdrawMethod->withdrawMethod->status == 1;
        })->pluck('withdrawMethod.name')->toArray();
    }
}
