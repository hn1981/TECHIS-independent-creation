<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamenImage extends Model
{
    use HasFactory;

    protected $fillable = [
    'ramen_id',
    'image_path',
    ];

    public function ramen() {
    return $this->belongsTo(Ramen::class);
    }

}
