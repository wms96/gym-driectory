<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'name', 'email', 'date_of_birth'];

    protected $dates = ['date_of_birth'];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }


}
