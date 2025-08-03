<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoraController;
use App\Http\Controllers\WorkflowController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('workflows', WorkflowController::class);
Route::resource('loras', LoraController::class);
Route::resource('generations', GenerationController::class)->except(['edit', 'update', 'destroy']);

Route::get('/api/generations/{generation}/status', [GenerationController::class, 'status'])->name('generations.status');
Route::get('/api/workflows/{workflow}/config', [GenerationController::class, 'workflowConfig'])->name('workflows.config');

Route::get('/images/{image}/download', [ImageController::class, 'download'])->name('images.download');
Route::get('/images/{image}/view', [ImageController::class, 'view'])->name('images.view');
