<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workflow extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'json_content',
        'field_config',
        'supports_lora',
        'is_active',
    ];

    protected $casts = [
        'field_config' => 'array',
        'supports_lora' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function loras(): BelongsToMany
    {
        return $this->belongsToMany(Lora::class, 'workflow_loras');
    }

    public function generations(): HasMany
    {
        return $this->hasMany(Generation::class);
    }

    public function getJsonContentArrayAttribute(): array
    {
        return json_decode($this->json_content, true) ?? [];
    }
}
