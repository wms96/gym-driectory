<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymImage extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'image_path', 'alt_text'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
