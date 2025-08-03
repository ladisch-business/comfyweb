@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Workflows
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('workflows.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add Workflow
            </a>
        </div>
    </div>

    @if($workflows->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($workflows as $workflow)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900">{{ $workflow->name }}</h3>
                            @if($workflow->description)
                            <p class="mt-1 text-sm text-gray-600">{{ $workflow->description }}</p>
                            @endif
                            <div class="mt-2 flex items-center space-x-4">
                                @if($workflow->supports_lora)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    LoRA Support
                                </span>
                                @endif
                                <span class="text-sm text-gray-500">
                                    {{ $workflow->field_config ? count($workflow->field_config) : 0 }} fields configured
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('workflows.show', $workflow) }}" 
                               class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View</a>
                            <a href="{{ route('workflows.edit', $workflow) }}" 
                               class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">Edit</a>
                        </div>
                    </div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No workflows</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating your first workflow.</p>
        <div class="mt-6">
            <a href="{{ route('workflows.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add Workflow
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
