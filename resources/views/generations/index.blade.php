@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                All Generations
            </h2>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('generations.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                New Generation
            </a>
        </div>
    </div>

    @if($generations->count() > 0)
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($generations as $generation)
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $generation->workflow->name }}</h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $generation->status === 'completed' ? 'bg-green-100 text-green-800' : 
                           ($generation->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($generation->status) }}
                    </span>
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ Str::limit($generation->prompt, 150) }}</p>
                
                @if($generation->images->count() > 0)
                <div class="grid grid-cols-2 gap-2 mb-4">
                    @foreach($generation->images->take(4) as $image)
                    <img src="{{ $image->thumbnail_url }}" alt="Generated image" 
                         class="w-full h-20 object-cover rounded">
                    @endforeach
                </div>
                @endif
                
                <div class="flex items-center justify-between text-sm text-gray-500">
                    <span>{{ $generation->batch_size }} images</span>
                    <span>{{ $generation->created_at->diffForHumans() }}</span>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('generations.show', $generation) }}" 
                       class="text-indigo-600 hover:text-indigo-500 text-sm font-medium">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="mt-6">
        {{ $generations->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No generations yet</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating your first image generation.</p>
        <div class="mt-6">
            <a href="{{ route('generations.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Generate Images
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
