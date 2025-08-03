<?php

namespace Database\Seeders;

use App\Models\Workflow;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $workflows = [
            [
                'name' => 'SDXL Base',
                'description' => 'Standard SDXL workflow with positive and negative prompts',
                'supports_lora' => true,
                'field_config' => ['prompt', 'negative_prompt'],
                'json_content' => json_encode([
                    "1" => [
                        "inputs" => [
                            "text" => "{{prompt}}",
                            "clip" => ["4", 1]
                        ],
                        "class_type" => "CLIPTextEncode"
                    ],
                    "2" => [
                        "inputs" => [
                            "text" => "{{negative_prompt}}",
                            "clip" => ["4", 1]
                        ],
                        "class_type" => "CLIPTextEncode"
                    ],
                    "3" => [
                        "inputs" => [
                            "seed" => 42,
                            "steps" => 20,
                            "cfg" => 8.0,
                            "sampler_name" => "euler",
                            "scheduler" => "normal",
                            "denoise" => 1.0,
                            "model" => ["4", 0],
                            "positive" => ["1", 0],
                            "negative" => ["2", 0],
                            "latent_image" => ["5", 0]
                        ],
                        "class_type" => "KSampler"
                    ],
                    "4" => [
                        "inputs" => [
                            "ckpt_name" => "sd_xl_base_1.0.safetensors"
                        ],
                        "class_type" => "CheckpointLoaderSimple"
                    ],
                    "5" => [
                        "inputs" => [
                            "width" => 1024,
                            "height" => 1024,
                            "batch_size" => 1
                        ],
                        "class_type" => "EmptyLatentImage"
                    ],
                    "6" => [
                        "inputs" => [
                            "samples" => ["3", 0],
                            "vae" => ["4", 2]
                        ],
                        "class_type" => "VAEDecode"
                    ],
                    "7" => [
                        "inputs" => [
                            "filename_prefix" => "ComfyUI",
                            "images" => ["6", 0]
                        ],
                        "class_type" => "SaveImage"
                    ]
                ])
            ],
            [
                'name' => 'Flux Dev',
                'description' => 'Flux development model workflow',
                'supports_lora' => true,
                'field_config' => ['prompt'],
                'json_content' => json_encode([
                    "1" => [
                        "inputs" => [
                            "text" => "{{prompt}}",
                            "clip" => ["2", 0]
                        ],
                        "class_type" => "CLIPTextEncode"
                    ],
                    "2" => [
                        "inputs" => [
                            "ckpt_name" => "flux1-dev.safetensors"
                        ],
                        "class_type" => "CheckpointLoaderSimple"
                    ],
                    "3" => [
                        "inputs" => [
                            "seed" => 42,
                            "steps" => 20,
                            "cfg" => 1.0,
                            "sampler_name" => "euler",
                            "scheduler" => "simple",
                            "denoise" => 1.0,
                            "model" => ["2", 0],
                            "positive" => ["1", 0],
                            "negative" => ["1", 0],
                            "latent_image" => ["4", 0]
                        ],
                        "class_type" => "KSampler"
                    ],
                    "4" => [
                        "inputs" => [
                            "width" => 1024,
                            "height" => 1024,
                            "batch_size" => 1
                        ],
                        "class_type" => "EmptyLatentImage"
                    ],
                    "5" => [
                        "inputs" => [
                            "samples" => ["3", 0],
                            "vae" => ["2", 2]
                        ],
                        "class_type" => "VAEDecode"
                    ],
                    "6" => [
                        "inputs" => [
                            "filename_prefix" => "ComfyUI",
                            "images" => ["5", 0]
                        ],
                        "class_type" => "SaveImage"
                    ]
                ])
            ]
        ];

        foreach ($workflows as $workflowData) {
            Workflow::create($workflowData);
        }
    }
}
