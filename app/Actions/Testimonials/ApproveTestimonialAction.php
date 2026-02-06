<?php

namespace App\Actions\Testimonials;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ApproveTestimonialAction
{
    public function execute(string $testimonialId, ?int $displayOrder = null): void
    {
        $testimonial = DB::table('testimonials')
            ->select('id', 'campaign_id')
            ->where('id', $testimonialId)
            ->first();

        if ($testimonial === null) {
            throw new RuntimeException('Testimonial not found.');
        }

        DB::table('testimonials')
            ->where('id', $testimonialId)
            ->update([
                'status' => 'approved',
                'rejection_reason' => null,
                'reviewed_at' => now(),
                'approved_at' => now(),
                'published_at' => now(),
                'display_order' => $displayOrder ?? 0,
                'updated_at' => now(),
            ]);

        Cache::forget(sprintf('campaign:%s:testimonials:approved', $testimonial->campaign_id));
        Cache::forget(sprintf('campaign:%s:testimonials:public', $testimonial->campaign_id));
    }
}
