<?php

namespace App\Http\Controllers;

use App\Models\Lora;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoraController extends Controller
{
    public function index(): View
    {
        $loras = Lora::where('is_active', true)->get();
        return view('loras.index', compact('loras'));
    }

    public function create(): View
    {
        return view('loras.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'trigger_word' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string',
        ]);

        Lora::create($request->only(['name', 'trigger_word', 'description', 'file_path']));

        return redirect()->route('loras.index')
            ->with('success', 'LoRA created successfully.');
    }

    public function show(Lora $lora): View
    {
        return view('loras.show', compact('lora'));
    }

    public function edit(Lora $lora): View
    {
        return view('loras.edit', compact('lora'));
    }

    public function update(Request $request, Lora $lora): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'trigger_word' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string',
        ]);

        $lora->update($request->only(['name', 'trigger_word', 'description', 'file_path']));

        return redirect()->route('loras.index')
            ->with('success', 'LoRA updated successfully.');
    }

    public function destroy(Lora $lora): RedirectResponse
    {
        $lora->update(['is_active' => false]);
        
        return redirect()->route('loras.index')
            ->with('success', 'LoRA deactivated successfully.');
    }
}
