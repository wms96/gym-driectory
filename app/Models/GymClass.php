<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = ['gym_id', 'name', 'description'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
