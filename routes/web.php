<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\TestimonialInbox;
use App\Livewire\Collector;
use Illuminate\Support\Facades\Route;

Route::get('/collector/{campaignId}', Collector::class)->name('collector.show');

Route::prefix('admin')->middleware('auth')->group(function (): void {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/testimonials', TestimonialInbox::class)->name('admin.inbox');
});
