@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                {{ $lora->name }}
            </h2>
            @if($lora->description)
            <p class="mt-1 text-sm text-gray-500">{{ $lora->description }}</p>
            @endif
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
            <a href="{{ route('loras.edit', $lora) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Edit
            </a>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">LoRA Details</h3>
            
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Trigger Word</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $lora->trigger_word }}
                        </span>
                    </dd>
                </div>
                
                @if($lora->file_path)
                <div>
                    <dt class="text-sm font-medium text-gray-500">File Path</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $lora->file_path }}</dd>
                </div>
                @endif
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lora->created_at->format('M j, Y g:i A') }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $lora->updated_at->format('M j, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>

        @if($lora->workflows->count() > 0)
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Compatible Workflows</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($lora->workflows as $workflow)
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900">{{ $workflow->name }}</h4>
                    @if($workflow->description)
                    <p class="text-sm text-gray-500 mt-1">{{ $workflow->description }}</p>
                    @endif
                    <div class="mt-2">
                        <a href="{{ route('workflows.show', $workflow) }}" 
                           class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                            View Workflow →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
