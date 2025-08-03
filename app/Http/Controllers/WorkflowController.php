<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkflowController extends Controller
{
    public function index(): View
    {
        $workflows = Workflow::where('is_active', true)->get();
        return view('workflows.index', compact('workflows'));
    }

    public function create(): View
    {
        return view('workflows.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'json_content' => 'required|json',
            'field_config' => 'required|json',
            'supports_lora' => 'boolean',
        ]);

        Workflow::create([
            'name' => $request->name,
            'description' => $request->description,
            'json_content' => $request->json_content,
            'field_config' => json_decode($request->field_config, true),
            'supports_lora' => $request->boolean('supports_lora'),
        ]);

        return redirect()->route('workflows.index')
            ->with('success', 'Workflow created successfully.');
    }

    public function show(Workflow $workflow): View
    {
        return view('workflows.show', compact('workflow'));
    }

    public function edit(Workflow $workflow): View
    {
        return view('workflows.edit', compact('workflow'));
    }

    public function update(Request $request, Workflow $workflow): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'json_content' => 'required|json',
            'field_config' => 'required|json',
            'supports_lora' => 'boolean',
        ]);

        $workflow->update([
            'name' => $request->name,
            'description' => $request->description,
            'json_content' => $request->json_content,
            'field_config' => json_decode($request->field_config, true),
            'supports_lora' => $request->boolean('supports_lora'),
        ]);

        return redirect()->route('workflows.index')
            ->with('success', 'Workflow updated successfully.');
    }

    public function destroy(Workflow $workflow): RedirectResponse
    {
        $workflow->update(['is_active' => false]);
        
        return redirect()->route('workflows.index')
            ->with('success', 'Workflow deactivated successfully.');
    }
}
