<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\Workflow;
use App\Services\ComfyUIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GenerationController extends Controller
{
    public function __construct(
        private ComfyUIService $comfyUIService
    ) {}

    public function index(): View
    {
        $generations = Generation::with(['workflow', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('generations.index', compact('generations'));
    }

    public function create(): View
    {
        $workflows = Workflow::where('is_active', true)->get();
        return view('generations.create', compact('workflows'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'workflow_id' => 'required|exists:workflows,id',
            'prompt' => 'required|string|max:2000',
            'negative_prompt' => 'nullable|string|max:2000',
            'batch_size' => 'required|integer|min:1|max:8',
            'selected_loras' => 'nullable|array',
            'selected_loras.*' => 'exists:loras,id',
        ]);

        $generation = Generation::create([
            'workflow_id' => $request->workflow_id,
            'prompt' => $request->prompt,
            'negative_prompt' => $request->negative_prompt,
            'batch_size' => $request->batch_size,
            'selected_loras' => $request->selected_loras,
            'status' => 'pending',
        ]);

        if ($this->comfyUIService->submitPrompt($generation)) {
            return redirect()->route('generations.show', $generation)
                ->with('success', 'Generation started successfully.');
        }

        return redirect()->back()
            ->with('error', 'Failed to start generation. Please try again.');
    }

    public function show(Generation $generation): View
    {
        $generation->load(['workflow', 'images']);
        return view('generations.show', compact('generation'));
    }

    public function status(Generation $generation): JsonResponse
    {
        $status = $this->comfyUIService->checkStatus($generation);
        $generation->refresh();
        
        return response()->json([
            'status' => $generation->status,
            'images' => $generation->images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'url' => $image->image_url,
                    'thumbnail_url' => $image->thumbnail_url,
                    'filename' => $image->filename,
                ];
            }),
            'error_message' => $generation->error_message,
        ]);
    }

    public function workflowConfig(Workflow $workflow): JsonResponse
    {
        $loras = $workflow->supports_lora ? $workflow->loras : collect();
        
        return response()->json([
            'field_config' => $workflow->field_config,
            'supports_lora' => $workflow->supports_lora,
            'loras' => $loras->map(function ($lora) {
                return [
                    'id' => $lora->id,
                    'name' => $lora->name,
                    'trigger_word' => $lora->trigger_word,
                    'description' => $lora->description,
                ];
            }),
        ]);
    }
}
