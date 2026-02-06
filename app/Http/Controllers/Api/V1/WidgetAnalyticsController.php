<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class WidgetAnalyticsController extends Controller
{
    public function trackView(string $testimonialId): JsonResponse
    {
        DB::table('testimonials')
            ->where('id', $testimonialId)
            ->increment('views_count');

        return response()->json(['ok' => true]);
    }

    public function trackClick(string $testimonialId): JsonResponse
    {
        DB::table('testimonials')
            ->where('id', $testimonialId)
            ->increment('click_count');

        return response()->json(['ok' => true]);
    }
}
