@extends('layouts.app')

@section('content')
<div x-data="generationForm()" class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Generate Images
            </h2>
        </div>
    </div>

    <form action="{{ route('generations.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Workflow Selection</h3>
            
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($workflows as $workflow)
                <label class="relative">
                    <input type="radio" name="workflow_id" value="{{ $workflow->id }}" 
                           x-model="selectedWorkflow" 
                           @change="loadWorkflowConfig({{ $workflow->id }})"
                           class="sr-only peer">
                    <div class="p-4 border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-indigo-500 peer-checked:bg-indigo-50 hover:border-gray-300 transition-colors">
                        <h4 class="font-medium text-gray-900">{{ $workflow->name }}</h4>
                        @if($workflow->description)
                        <p class="text-sm text-gray-600 mt-1">{{ $workflow->description }}</p>
                        @endif
                        @if($workflow->supports_lora)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                            LoRA Support
                        </span>
                        @endif
                    </div>
                </label>
                @endforeach
            </div>
            
            @error('workflow_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div x-show="selectedWorkflow" class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Generation Settings</h3>
            
            <div class="space-y-4">
                <div>
                    <label for="prompt" class="block text-sm font-medium text-gray-700">Prompt</label>
                    <textarea name="prompt" id="prompt" rows="3" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Describe what you want to generate..."
                              required>{{ old('prompt') }}</textarea>
                    @error('prompt')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="workflowConfig.field_config && workflowConfig.field_config.includes('negative_prompt')">
                    <label for="negative_prompt" class="block text-sm font-medium text-gray-700">Negative Prompt</label>
                    <textarea name="negative_prompt" id="negative_prompt" rows="2" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="What you don't want in the image...">{{ old('negative_prompt') }}</textarea>
                    @error('negative_prompt')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="batch_size" class="block text-sm font-medium text-gray-700">Number of Images</label>
                        <select name="batch_size" id="batch_size" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('batch_size', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        @error('batch_size')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div x-show="workflowConfig.supports_lora && workflowConfig.loras && workflowConfig.loras.length > 0">
                    <label class="block text-sm font-medium text-gray-700 mb-2">LoRAs (Optional)</label>
                    <div class="space-y-2">
                        <template x-for="lora in workflowConfig.loras" :key="lora.id">
                            <label class="flex items-center">
                                <input type="checkbox" :value="lora.id" name="selected_loras[]" 
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-900" x-text="lora.name"></span>
                                <span class="ml-2 text-xs text-gray-500" x-text="'(' + lora.trigger_word + ')'"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="selectedWorkflow" class="flex justify-end">
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate Images
            </button>
        </div>
    </form>
</div>

<script>
function generationForm() {
    return {
        selectedWorkflow: null,
        workflowConfig: {},
        
        async loadWorkflowConfig(workflowId) {
            try {
                const response = await fetch(`/api/workflows/${workflowId}/config`);
                this.workflowConfig = await response.json();
            } catch (error) {
                console.error('Failed to load workflow config:', error);
            }
        }
    }
}
</script>
@endsection
