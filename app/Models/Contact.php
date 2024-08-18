<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'type', 'value'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
