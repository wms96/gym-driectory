<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachImage extends Model
{
    use HasFactory;

    protected $fillable = ['coach_id', 'image_path', 'alt_text'];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }
}
