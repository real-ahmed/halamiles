<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Store extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function cashbacktype()
    {
        return $this->belongsTo(Cashbacktype::class);
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function categories()
    {
        return $this->hasMany(StoresCategory::class);
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

    public function scopeFeatured()
    {
        return $this->where('featured', 1);
    }
    public function favorite()
    {
        return $this->hasMany(Favorite::class);
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'store_countries', 'store_id', 'country_id');
    }
    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'store_channels', 'store_id', 'channel_id');
    }

    public function getCashbackAttribute($date = null)
    {
        // Use Carbon instance for consistency
        if (is_null($date)) {
            $date = Carbon::now();
        } elseif (!($date instanceof Carbon)) {
            // If $date is not a Carbon instance, try parsing it
            try {
                $date = Carbon::parse($date);
            } catch (\Exception $e) {
                // If parsing fails, default to current time
                $date = Carbon::now();
            }
        }
    
        // Check if the cashback attribute exists and has a value
        if (isset($this->attributes['offer_cashback']) && isset($this->attributes['ending_date'])) {
            $endingDate = Carbon::parse($this->attributes['ending_date']);
    
            // Check if the offer is still valid
            if ($endingDate->gte($date)) {
                return $this->attributes['offer_cashback']; // Return offer cashback
            }
        }
    
        // Check if the cashback attribute exists and has a value
        if (isset($this->attributes['cashback'])) {
            return $this->attributes['cashback']; // Return regular cashback
        }
    
        return 0; // Return 0 if neither offer cashback nor regular cashback is available
    }
    

    function gethasOfferAttribute($date = null)
    {
        if (is_null($date)) {
            $date = Carbon::now();
        } elseif (!($date instanceof Carbon)) {
            // If $date is not a Carbon instance, try parsing it
            try {
                $date = Carbon::parse($date);
            } catch (\Exception $e) {
                // If parsing fails, default to current time
                $date = Carbon::now();
            }
        }
    
        // Check if the cashback attribute exists and has a value
        if (isset($this->attributes['offer_cashback']) && isset($this->attributes['ending_date'])) {
            $endingDate = Carbon::parse($this->attributes['ending_date']);
    
            // Check if the offer is still valid
            if ($endingDate->gte($date)) {
                return true;
            }
        }
    
        // Check if the cashback attribute exists and has a value
        if (isset($this->attributes['cashback'])) {
            return false;
        }
    
        return 0; // Return 0 if neither offer cashback nor regular cashback is available

    }

    public function getTermsAttribute()
    {
        if ($this->attributes['terms'] == null) {
            return GeneralSetting::first()->stander_terms;
        } else {
            return $this->attributes['terms'];
        }
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'model');
    }


    public function clicks()
    {
        return $this->morphMany(Click::class, 'model');
    }

    public function getViewsAttribute()
    {
        return $this->clicks->where("type", "View")->count();
    }
    public function getTitleAttribute()
    {
        return $this->name;
    }
}
