<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use App\Models\Workflow;
use App\Services\ComfyUIService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private ComfyUIService $comfyUIService
    ) {}

    public function index(): View
    {
        $workflows = Workflow::where('is_active', true)->get();
        $recentGenerations = Generation::with(['workflow', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
        
        $comfyUIHealthy = $this->comfyUIService->isHealthy();
        
        $stats = [
            'total_generations' => Generation::count(),
            'completed_generations' => Generation::where('status', 'completed')->count(),
            'total_images' => \App\Models\GeneratedImage::count(),
            'active_workflows' => Workflow::where('is_active', true)->count(),
        ];

        return view('dashboard', compact('workflows', 'recentGenerations', 'comfyUIHealthy', 'stats'));
    }
}
