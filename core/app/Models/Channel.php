<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_channels', 'channel_id', 'store_id');
    }

        public function coupons()
    {
        return $this->belongsToMany(Store::class, 'coupon_channels', 'channel_id', 'coupon_id');
    }

        public function products()
    {
        return $this->belongsToMany(Store::class, 'product_channels', 'channel_id', 'product_id');
    }
}