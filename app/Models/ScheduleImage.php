<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleImage extends Model
{
    use HasFactory;

    protected $fillable = ['schedule_id', 'image_path', 'alt_text'];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
