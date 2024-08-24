<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'name', 'description', 'main_image', 'images'];

    protected $casts = [
        'images' => 'array',  // Cast the 'images' field as an array
    ];

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class);
    }

    // Getter for main image
    public function getMainImageAttribute()
    {
        return $this->main_image;
    }

    // Getter for multiple images
    public function getImagesAttribute($value)
    {
        return json_decode($value, true);
    }

    // Setter for multiple images
    public function setImagesAttribute($value)
    {
        $this->attributes['images'] = json_encode($value);
    }
}
