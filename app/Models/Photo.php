<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Photo extends Model
{
    protected $fillable = [
        'booking_id',
        'file_path',
        'file_name',
        'file_size',
        'is_collage',
    ];

    protected function casts(): array
    {
        return [
            'is_collage' => 'boolean',
        ];
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
