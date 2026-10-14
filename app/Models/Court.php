<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'price_per_hour',
        'status',
        'description',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}