<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;
    protected $fillable = [
        'shop_id',
        'feedName',
        'category',
        'collection_id',
        'collection_handle',
        'variants',
        'status',
    ];
}
