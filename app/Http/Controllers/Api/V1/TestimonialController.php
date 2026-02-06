<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Testimonials\GeneratePresignedUploadUrlAction;
use App\Actions\Testimonials\StoreTestimonialMetadataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PresignTestimonialUploadRequest;
use App\Http\Requests\Api\V1\StoreTestimonialRequest;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function presign(
        PresignTestimonialUploadRequest $request,
        GeneratePresignedUploadUrlAction $generatePresignedUploadUrlAction,
    ): JsonResponse {
        $data = $request->validated();

        $result = $generatePresignedUploadUrlAction->execute(
            $data['campaign_id'],
            $data['extension'] ?? 'webm',
            $data['expires_in_minutes'] ?? 15,
        );

        return response()->json([
            'data' => $result,
        ]);
    }

    public function store(
        StoreTestimonialRequest $request,
        StoreTestimonialMetadataAction $storeTestimonialMetadataAction,
    ): JsonResponse {
        $payload = $request->validated();
        $payload['status'] ??= 'pending_review';

        $testimonialId = $storeTestimonialMetadataAction->execute($payload);

        return response()->json([
            'data' => [
                'id' => $testimonialId,
                'status' => $payload['status'],
            ],
        ], 201);
    }
}
