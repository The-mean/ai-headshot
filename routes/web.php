<?php

use App\Livewire\Collector;
use Illuminate\Support\Facades\Route;

Route::get('/collector/{campaignId}', Collector::class)->name('collector.show');
