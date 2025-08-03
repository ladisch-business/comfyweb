<?php

namespace Database\Seeders;

use App\Models\Lora;
use App\Models\Workflow;
use Illuminate\Database\Seeder;

class LoraSeeder extends Seeder
{
    public function run(): void
    {
        $loras = [
            [
                'name' => 'Realistic Vision',
                'trigger_word' => 'realistic',
                'description' => 'Enhances photorealistic rendering',
                'file_path' => '/models/loras/realistic_vision.safetensors'
            ],
            [
                'name' => 'Anime Style',
                'trigger_word' => 'anime style',
                'description' => 'Converts images to anime/manga style',
                'file_path' => '/models/loras/anime_style.safetensors'
            ],
            [
                'name' => 'Portrait Enhancement',
                'trigger_word' => 'portrait',
                'description' => 'Improves facial features and portrait quality',
                'file_path' => '/models/loras/portrait_enhancement.safetensors'
            ]
        ];

        foreach ($loras as $loraData) {
            $lora = Lora::create($loraData);
            
            $workflows = Workflow::where('supports_lora', true)->get();
            $lora->workflows()->attach($workflows->pluck('id'));
        }
    }
}
