<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponCountry extends Model
{
    use HasFactory;
        protected $table = 'coupon_countries';
    
    protected $fillable = [
        'coupon_id',
        'country_id',
    ];
}
