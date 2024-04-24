<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Click extends Model
{
    use HasFactory;

    public function model()
    {
        return $this->morphTo();
    }


    public function claim()
    {
        return $this->morphTo();
    }

    public function getTypeAttribute($value)
    {
        $returnTypes = [
            0 => 'View',
            1 => 'Visit',
            3 => 'Copy',];

        return $returnTypes[$value] ?? 'unknown';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
        
    }
    public function getIsClaimAttribute()
    {
        if ($this->claim) {
            return false;
        }
        $now = Carbon::now();
        $oneWeekAgo = $now->copy()->subWeek();
        $twoWeeksAgo = $now->copy()->subWeeks(3);
    
        // Assuming $this->created_at is a Carbon instance
        if ($this->created_at > $oneWeekAgo || $this->created_at <= $twoWeeksAgo) {
            return false;
        }
    
        return true;
    }


}