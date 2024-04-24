<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClickTransaction extends Model
{
    use HasFactory;

    public function click()
    {
        return $this->belongsTo(Click::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}