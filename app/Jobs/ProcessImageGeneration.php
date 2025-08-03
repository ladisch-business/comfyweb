<?php

namespace App\Jobs;

use App\Models\Generation;
use App\Services\ComfyUIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessImageGeneration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private Generation $generation
    ) {}

    public function handle(ComfyUIService $comfyUIService): void
    {
        try {
            if ($comfyUIService->submitPrompt($this->generation)) {
                while ($this->generation->status === 'processing') {
                    sleep(env('COMFYUI_POLL_INTERVAL', 2));
                    $comfyUIService->checkStatus($this->generation);
                    $this->generation->refresh();
                }
            }
        } catch (\Exception $e) {
            Log::error('Image generation job failed: ' . $e->getMessage());
            $this->generation->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }
}
