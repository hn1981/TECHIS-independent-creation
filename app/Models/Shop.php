<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    public function user() {
    return $this->belongsTo(User::class);
    }

    public function prefecture() {
    return $this->belongsTo(Prefecture::class);
    }

    public function ramens() {
    return $this->hasMany(Ramen::class);
    }

    public function shopImages() {
    return $this->hasMany(ShopImage::class);
    }

}
