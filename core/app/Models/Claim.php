<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;


    public function click()
    {
        return $this->belongsTo(Click::class);
    }
    public function issue()
    {
        return $this->belongsTo(ClaimIssue::class);
    }

    public function scopePending()
    {
        return $this->where('status',0);
    }
    public function scopeApproved($query)

    {

        return $this->where('status',1);

    }


    public function scopeRejected($query)

    {

        return $this->where('status',2);

    }
}
