<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'addressable_type', 'addressable_id', 'street', 'city', 'state', 'country',
        'postal_code', 'latitude', 'longitude',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }
}
