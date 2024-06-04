<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoresCategory extends Model
{
    use HasFactory;

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function withdrawMethods()
    {
        return $this->morphMany(ModelWithdrawMethod::class, 'model');
    }

    public function cashbacktype()
    {
        return $this->belongsTo(Cashbacktype::class);
    }

    public function clicks()
    {
        return $this->morphMany(Click::class, 'model');
    }
}
