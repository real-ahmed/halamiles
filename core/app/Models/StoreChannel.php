<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreChannel extends Model
{
    use HasFactory;
    
    protected $table = 'store_channels';
    
    protected $fillable = [
        'store_id',
        'channel_id',
    ];
}
