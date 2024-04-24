<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $casts = [
        'ending_date' => 'datetime'
    ];



    public function cashbacktype()
    {
        return $this->belongsTo(Cashbacktype::class, 'cashback_type');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'product_countries', 'product_id', 'country_id');
    }
    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'product_channels', 'product_id', 'channel_id');
    }


    public function scopeActive()
    {
        return $this->where('status', 1)->where('ending_date', '>', now())->whereHas('store', function ($q) {
            $q->where('status', 1);
        });
    }

    public function scopeInactive()
    {
        return $this->where('status', 2)->where('ending_date', '>', now());
    }

    public function scopeExpired()
    {
        return $this->where(function ($query) {
            $query->where('status', 1)->orWhere('status', 2);
        })->where('ending_date', '<=', now());
    }

    public function scopeTrend()
    {
        return $this->where('trend', 1);
    }
    public function clicks()
    {
        return $this->morphMany(Click::class, 'model');
    }
}
