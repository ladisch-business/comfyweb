<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'generation_id',
        'image_path',
        'thumbnail_path',
        'filename',
        'file_size',
    ];

    public function generation(): BelongsTo
    {
        return $this->belongsTo(Generation::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail_path 
            ? asset('storage/' . $this->thumbnail_path)
            : $this->image_url;
    }
}
