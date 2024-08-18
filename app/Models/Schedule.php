<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'class_id', 'coach_id', 'start_time', 'end_time'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function class()
    {
        return $this->belongsTo(GymClass::class);
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function images()
    {
        return $this->hasMany(ScheduleImage::class);
    }
}
