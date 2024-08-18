<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'name', 'description', 'price', 'duration_days'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }
}
