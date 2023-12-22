<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamenImage extends Model
{
    use HasFactory;

    public function ramen() {
    return $this->belongsTo(Ramen::class);
    }

}
