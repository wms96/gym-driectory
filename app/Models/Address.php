<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_id', 'street', 'city', 'state', 'country',
        'postal_code', 'latitude', 'longitude',
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }
}
