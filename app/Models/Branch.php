<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'name'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }

    public function classes()
    {
        return $this->hasMany(GymClass::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }
}
