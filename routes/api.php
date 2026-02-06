<?php

use App\Http\Controllers\Api\V1\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/testimonials/presign', [TestimonialController::class, 'presign']);
    Route::post('/testimonials', [TestimonialController::class, 'store']);
});
