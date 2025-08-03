@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                LoRAs
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('loras.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add LoRA
            </a>
        </div>
    </div>

    @if($loras->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @foreach($loras as $lora)
            <li>
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900">{{ $lora->name }}</h3>
                            <div class="mt-1 flex items-center space-x-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $lora->trigger_word }}
                                </span>
                                @if($lora->description)
                                <span class="text-sm text-gray-500">{{ $lora->description }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('loras.show', $lora) }}" 
                               class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">View</a>
                            <a href="{{ route('loras.edit', $lora) }}" 
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM6 6v12h8V6H6z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No LoRAs</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by adding your first LoRA.</p>
        <div class="mt-6">
            <a href="{{ route('loras.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Add LoRA
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
