<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCountry extends Model
{
    use HasFactory;
    protected $table = 'store_countries';
    
    protected $fillable = [
        'store_id',
        'country_id',
    ];
}
