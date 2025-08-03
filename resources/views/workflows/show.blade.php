@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ $workflow->name }}
            </h2>
            @if($workflow->description)
            <p class="mt-1 text-sm text-gray-500">{{ $workflow->description }}</p>
            @endif
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('workflows.edit', $workflow) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Edit
            </a>
            <a href="{{ route('generations.create') }}?workflow={{ $workflow->id }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Use Workflow
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration</h3>
            
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">LoRA Support</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $workflow->supports_lora ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $workflow->supports_lora ? 'Enabled' : 'Disabled' }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Field Configuration</dt>
                    <dd class="mt-1">
                        @foreach($workflow->field_config as $field)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                {{ $field }}
                            </span>
                        @endforeach
                    </dd>
                </div>
            </dl>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Workflow JSON</h3>
            <pre class="bg-gray-50 rounded-md p-4 overflow-x-auto text-sm"><code>{{ json_encode($workflow->json_content_array, JSON_PRETTY_PRINT) }}</code></pre>
        </div>

        @if($workflow->supports_lora && $workflow->loras->count() > 0)
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Associated LoRAs</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($workflow->loras as $lora)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900">{{ $lora->name }}</h4>
                    <p class="text-sm text-gray-600 mt-1">Trigger: {{ $lora->trigger_word }}</p>
                    @if($lora->description)
                    <p class="text-sm text-gray-500 mt-2">{{ $lora->description }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
