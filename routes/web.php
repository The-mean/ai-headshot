<?php

use App\Livewire\Admin\CampaignAnalytics;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\TestimonialInbox;
use App\Livewire\Admin\WidgetBuilder;
use App\Livewire\Collector;
use Illuminate\Support\Facades\Route;

Route::get('/collector/{campaignId}', Collector::class)->name('collector.show');

Route::prefix('admin')->middleware('auth')->group(function (): void {
    Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/testimonials', TestimonialInbox::class)->name('admin.inbox');
    Route::get('/widget-builder', WidgetBuilder::class)->name('admin.widget-builder');
    Route::get('/campaigns/{campaignId}', CampaignAnalytics::class)->name('admin.campaign.analytics');
});
