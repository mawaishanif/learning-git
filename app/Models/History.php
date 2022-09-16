<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    
    protected $table = "trigger_history";
    
    protected $fillable = [
        'id',
        'shop_id',
        'event',
        'event_id',
        'pixel',
        'data',
    ];
}
