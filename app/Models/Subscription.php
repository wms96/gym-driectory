<?php

namespace App\Models;


namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'duration_days', 'subscribable_id', 'subscribable_type'];

    public function subscribable()
    {
        return $this->morphTo();
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }
}
