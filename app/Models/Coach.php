<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'name', 'specialization', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(CoachImage::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
