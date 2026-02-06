<?php

namespace App\Livewire\Admin;

use App\Actions\Testimonials\ApproveTestimonialAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class TestimonialInbox extends Component
{
    public ?string $previewUrl = null;

    public ?string $previewId = null;

    public string $statusFilter = 'all';

    public function approve(string $testimonialId, ApproveTestimonialAction $approveTestimonialAction): void
    {
        $approveTestimonialAction->execute($testimonialId);
        $this->dispatch('toast', type: 'success', message: 'Video approved.');
    }

    public function reject(string $testimonialId): void
    {
        $testimonial = DB::table('testimonials')->select('campaign_id')->where('id', $testimonialId)->first();

        if ($testimonial === null) {
            $this->dispatch('toast', type: 'error', message: 'Video not found.');

            return;
        }

        DB::table('testimonials')
            ->where('id', $testimonialId)
            ->update([
                'status' => 'rejected',
                'rejection_reason' => 'Rejected by admin',
                'reviewed_at' => now(),
                'updated_at' => now(),
            ]);

        Cache::forget(sprintf('campaign:%s:testimonials:approved', $testimonial->campaign_id));
        Cache::forget(sprintf('campaign:%s:testimonials:public', $testimonial->campaign_id));

        $this->dispatch('toast', type: 'success', message: 'Video rejected.');
    }

    public function openPreview(string $testimonialId): void
    {
        $testimonial = DB::table('testimonials')
            ->select('id', 'storage_path', 'storage_disk')
            ->where('id', $testimonialId)
            ->first();

        if ($testimonial === null) {
            $this->dispatch('toast', type: 'error', message: 'Video not found.');

            return;
        }

        $disk = $testimonial->storage_disk ?: 'r2';
        $this->previewUrl = Storage::disk($disk)->url($testimonial->storage_path);
        $this->previewId = $testimonialId;
    }

    public function closePreview(): void
    {
        $this->previewUrl = null;
        $this->previewId = null;
    }

    public function render()
    {
        $query = DB::table('testimonials')
            ->join('campaigns', 'campaigns.id', '=', 'testimonials.campaign_id')
            ->select(
                'testimonials.id',
                'testimonials.author_name',
                'testimonials.status',
                'testimonials.source',
                'testimonials.created_at',
                'campaigns.name as campaign_name',
                'campaigns.id as campaign_id'
            )
            ->orderByDesc('testimonials.created_at');

        if ($this->statusFilter !== 'all') {
            $query->where('testimonials.status', $this->statusFilter);
        }

        return view('livewire.admin.testimonial-inbox', [
            'items' => $query->limit(50)->get(),
        ]);
    }
}
