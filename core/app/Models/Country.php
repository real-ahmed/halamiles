<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    
    
        public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_countries', 'country_id', 'store_id');
    }



    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_countries', 'country_id', 'coupon_id');
    }


        public function products()
    {
        return $this->belongsToMany(Coupon::class, 'product_countries', 'country_id', 'product_id');
    }
}