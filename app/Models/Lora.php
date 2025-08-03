<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lora extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trigger_word',
        'description',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workflows(): BelongsToMany
    {
        return $this->belongsToMany(Workflow::class, 'workflow_loras');
    }
}
