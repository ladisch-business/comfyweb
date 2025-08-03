@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Create Workflow
            </h2>
        </div>
    </div>

    <form action="{{ route('workflows.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="json_content" class="block text-sm font-medium text-gray-700">Workflow JSON</label>
                    <textarea name="json_content" id="json_content" rows="10" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                              placeholder='{"1": {"inputs": {"text": "{{prompt}}"}, "class_type": "CLIPTextEncode"}}'
                              required>{{ old('json_content') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Paste your ComfyUI workflow JSON here. Use {{prompt}} as placeholder for user input.</p>
                    @error('json_content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="field_config" class="block text-sm font-medium text-gray-700">Field Configuration</label>
                    <textarea name="field_config" id="field_config" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono text-sm"
                              placeholder='["prompt", "negative_prompt"]'
                              required>{{ old('field_config') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">JSON array of field names to show in the UI. Available: "prompt", "negative_prompt"</p>
                    @error('field_config')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="supports_lora" id="supports_lora" value="1" 
                           {{ old('supports_lora') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="supports_lora" class="ml-2 block text-sm text-gray-900">
                        Supports LoRA
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('workflows.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Create Workflow
            </button>
        </div>
    </form>
</div>
@endsection
