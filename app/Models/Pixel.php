<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pixel extends Model
{
    use HasFactory;
     protected $fillable = [
        'pixel_id','shop_id','type','collection','tag','access_token','test_token','status','created_at','updated_at'
    ];
}
