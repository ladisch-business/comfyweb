@extends('layouts.app')

@section('content')
<div x-data="generationStatus({{ $generation->id }})" class="max-w-6xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-6">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Generation Details
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Workflow: {{ $generation->workflow->name }} • 
                Created: {{ $generation->created_at->format('M j, Y g:i A') }}
            </p>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('generations.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                New Generation
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Generation Info</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span x-text="status" 
                                  :class="{
                                      'bg-green-100 text-green-800': status === 'completed',
                                      'bg-red-100 text-red-800': status === 'failed',
                                      'bg-yellow-100 text-yellow-800': status === 'processing',
                                      'bg-gray-100 text-gray-800': status === 'pending'
                                  }"
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize">
                            </span>
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Batch Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $generation->batch_size }} images</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Prompt</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $generation->prompt }}</dd>
                    </div>
                    
                    @if($generation->negative_prompt)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Negative Prompt</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $generation->negative_prompt }}</dd>
                    </div>
                    @endif
                    
                    @if($generation->selected_loras)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Selected LoRAs</dt>
                        <dd class="mt-1">
                            @foreach($generation->workflow->loras->whereIn('id', $generation->selected_loras) as $lora)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                    {{ $lora->name }}
                                </span>
                            @endforeach
                        </dd>
                    </div>
                    @endif
                </dl>
                
                <div x-show="status === 'processing'" class="mt-4">
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600"></div>
                        <span class="ml-2 text-sm text-gray-600">Processing...</span>
                    </div>
                </div>
                
                <div x-show="errorMessage" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-sm text-red-600" x-text="errorMessage"></p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Generated Images</h3>
                
                <div x-show="images.length === 0 && status !== 'completed'" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No images yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Images will appear here once generation is complete.</p>
                </div>
                
                <div x-show="images.length === 0 && status === 'completed'" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No images generated</h3>
                    <p class="mt-1 text-sm text-gray-500">The generation completed but no images were produced.</p>
                </div>
                
                <div x-show="images.length > 0" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <template x-for="image in images" :key="image.id">
                        <div class="relative group">
                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
                                <img :src="image.thumbnail_url" :alt="image.filename" 
                                     class="h-full w-full object-cover object-center group-hover:opacity-75 cursor-pointer"
                                     @click="openImageModal(image)">
                            </div>
                            <div class="mt-2 flex justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 truncate" x-text="image.filename"></p>
                                </div>
                                <div class="flex space-x-2">
                                    <a :href="'/images/' + image.id + '/view'" target="_blank"
                                       class="text-indigo-600 hover:text-indigo-500 text-sm">View</a>
                                    <a :href="'/images/' + image.id + '/download'"
                                       class="text-indigo-600 hover:text-indigo-500 text-sm">Download</a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generationStatus(generationId) {
    return {
        status: '{{ $generation->status }}',
        images: @json($generation->images->map(function($image) {
            return [
                'id' => $image->id,
                'url' => $image->image_url,
                'thumbnail_url' => $image->thumbnail_url,
                'filename' => $image->filename,
            ];
        })),
        errorMessage: '{{ $generation->error_message }}',
        pollInterval: null,
        
        init() {
            if (this.status === 'processing' || this.status === 'pending') {
                this.startPolling();
            }
        },
        
        startPolling() {
            this.pollInterval = setInterval(async () => {
                await this.checkStatus();
            }, 3000);
        },
        
        stopPolling() {
            if (this.pollInterval) {
                clearInterval(this.pollInterval);
                this.pollInterval = null;
            }
        },
        
        async checkStatus() {
            try {
                const response = await fetch(`/api/generations/${generationId}/status`);
                const data = await response.json();
                
                this.status = data.status;
                this.images = data.images || [];
                this.errorMessage = data.error_message;
                
                if (this.status === 'completed' || this.status === 'failed') {
                    this.stopPolling();
                }
            } catch (error) {
                console.error('Failed to check status:', error);
            }
        },
        
        openImageModal(image) {
            window.open(image.url, '_blank');
        }
    }
}
</script>
@endsection
