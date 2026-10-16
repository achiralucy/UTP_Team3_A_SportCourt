<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Court extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'futsal' => 'Futsal',
        'badminton' => 'Badminton',
    ];

    public const STATUSES = [
        'available' => 'Tersedia',
        'maintenance' => 'Maintenance',
    ];

    protected $fillable = [
        'name',
        'category',
        'price_per_hour',
        'status',
        'facilities',
        'image',
        'description',
    ];

    protected $casts = [
        'price_per_hour' => 'integer',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeSearch(Builder $query, ?string $keyword): Builder
    {
        if (blank($keyword)) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('facilities', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        return filled($category) && $category !== 'all'
            ? $query->where('category', $category)
            : $query;
    }

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return filled($status) && $status !== 'all'
            ? $query->where('status', $status)
            : $query;
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image || ! Storage::disk('public')->exists($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function getFacilityListAttribute(): array
    {
        if (blank($this->facilities)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $this->facilities))));
    }

    public function hasActiveBookings(): bool
    {
        return $this->bookings()
            ->whereIn('status', ['pending', 'approved'])
            ->where('booking_date', '>=', now()->toDateString())
            ->exists();
    }

    public function deleteImage(): void
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }
    }
}