<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'rating',
        'comment',
        'member_id',
        'reviewable_id',
        'reviewable_type',
    ];

    public function reviewable()
    {
        return $this->morphTo();
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
