<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'prompt',
        'negative_prompt',
        'batch_size',
        'status',
        'comfyui_prompt_id',
        'selected_loras',
        'error_message',
    ];

    protected $casts = [
        'selected_loras' => 'array',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(GeneratedImage::class);
    }
}
