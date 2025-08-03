<?php

namespace App\Services;

use App\Models\Generation;
use App\Models\GeneratedImage;
use App\Models\Workflow;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ComfyUIService
{
    private Client $client;
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = env('COMFYUI_URL', 'http://comfyui:8188');
        $this->timeout = env('COMFYUI_TIMEOUT', 300);
        
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $this->timeout,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function submitPrompt(Generation $generation): bool
    {
        try {
            $workflow = $generation->workflow;
            $promptData = $this->buildPromptData($generation, $workflow);

            $response = $this->client->post('/prompt', [
                'json' => [
                    'prompt' => $promptData,
                    'client_id' => Str::uuid()->toString(),
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            if (isset($result['prompt_id'])) {
                $generation->update([
                    'comfyui_prompt_id' => $result['prompt_id'],
                    'status' => 'processing'
                ]);
                return true;
            }

            return false;
        } catch (RequestException $e) {
            Log::error('ComfyUI API Error: ' . $e->getMessage());
            $generation->update([
                'status' => 'failed',
                'error_message' => 'ComfyUI API Error: ' . $e->getMessage()
            ]);
            return false;
        }
    }

    public function checkStatus(Generation $generation): string
    {
        if (!$generation->comfyui_prompt_id) {
            return 'failed';
        }

        try {
            $response = $this->client->get("/history/{$generation->comfyui_prompt_id}");
            $history = json_decode($response->getBody()->getContents(), true);

            if (empty($history)) {
                return 'processing';
            }

            $promptHistory = $history[$generation->comfyui_prompt_id] ?? null;
            
            if (!$promptHistory) {
                return 'processing';
            }

            if (isset($promptHistory['status']['completed']) && $promptHistory['status']['completed']) {
                $this->processCompletedGeneration($generation, $promptHistory);
                return 'completed';
            }

            if (isset($promptHistory['status']['status_str']) && $promptHistory['status']['status_str'] === 'error') {
                $generation->update([
                    'status' => 'failed',
                    'error_message' => 'ComfyUI processing failed'
                ]);
                return 'failed';
            }

            return 'processing';
        } catch (RequestException $e) {
            Log::error('ComfyUI Status Check Error: ' . $e->getMessage());
            return 'processing';
        }
    }

    private function buildPromptData(Generation $generation, Workflow $workflow): array
    {
        $workflowData = $workflow->json_content_array;
        $prompt = $generation->prompt;

        if ($generation->selected_loras && is_array($generation->selected_loras)) {
            foreach ($generation->selected_loras as $loraId) {
                $lora = $workflow->loras()->find($loraId);
                if ($lora) {
                    $prompt = $lora->trigger_word . ', ' . $prompt;
                }
            }
        }

        foreach ($workflowData as $nodeId => &$node) {
            if (isset($node['inputs'])) {
                if (isset($node['inputs']['text']) && strpos($node['inputs']['text'], '{{prompt}}') !== false) {
                    $node['inputs']['text'] = str_replace('{{prompt}}', $prompt, $node['inputs']['text']);
                }
                
                if (isset($node['inputs']['negative']) && $generation->negative_prompt) {
                    $node['inputs']['negative'] = $generation->negative_prompt;
                }
                
                if (isset($node['inputs']['batch_size'])) {
                    $node['inputs']['batch_size'] = $generation->batch_size;
                }
            }
        }

        return $workflowData;
    }

    private function processCompletedGeneration(Generation $generation, array $promptHistory): void
    {
        $outputs = $promptHistory['outputs'] ?? [];
        
        foreach ($outputs as $nodeId => $output) {
            if (isset($output['images'])) {
                foreach ($output['images'] as $imageData) {
                    $this->downloadAndSaveImage($generation, $imageData);
                }
            }
        }

        $generation->update(['status' => 'completed']);
    }

    private function downloadAndSaveImage(Generation $generation, array $imageData): void
    {
        try {
            $filename = $imageData['filename'];
            $subfolder = $imageData['subfolder'] ?? '';
            $type = $imageData['type'] ?? 'output';

            $imageUrl = "{$this->baseUrl}/view?" . http_build_query([
                'filename' => $filename,
                'subfolder' => $subfolder,
                'type' => $type,
            ]);

            $response = $this->client->get($imageUrl);
            $imageContent = $response->getBody()->getContents();

            $storagePath = 'generated/' . $generation->id;
            $imagePath = $storagePath . '/' . $filename;

            Storage::disk('public')->put($imagePath, $imageContent);

            GeneratedImage::create([
                'generation_id' => $generation->id,
                'image_path' => $imagePath,
                'filename' => $filename,
                'file_size' => strlen($imageContent),
            ]);

        } catch (RequestException $e) {
            Log::error('Image Download Error: ' . $e->getMessage());
        }
    }

    public function isHealthy(): bool
    {
        try {
            $response = $this->client->get('/system_stats', ['timeout' => 5]);
            return $response->getStatusCode() === 200;
        } catch (RequestException $e) {
            return false;
        }
    }
}
