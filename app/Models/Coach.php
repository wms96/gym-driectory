<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = ['gym_id', 'name', 'specialization', 'description', 'is_freelancer', 'preferred_gyms', 'price_range', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
        'preferred_gyms' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(CoachImage::class);
    }

    public function gym()
    {
        return $this->belongsTo(Gym::class);
    }

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function scopeFilterByRating($query, $rating)
    {
        return $query->whereHas('reviews', function($query) use ($rating) {
            $query->where('rating', $rating);
        });
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
