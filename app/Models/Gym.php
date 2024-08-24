<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(GymImage::class);
    }

    public function owners()
    {
        return $this->belongsToMany(Owner::class);
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function classes()
    {
        return $this->hasMany(GymClass::class);
    }

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }

    public function facilities()
    {
        return $this->hasMany(Facility::class);
    }

    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribable');
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
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
}
