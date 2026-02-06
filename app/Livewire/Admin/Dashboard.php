<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @return array<string, int|float|bool>
     */
    public function stats(): array
    {
        $monthStart = Carbon::now()->startOfMonth();

        $totalVideos = (int) DB::table('testimonials')->count();
        $totalCampaigns = (int) DB::table('campaigns')->count();

        $uploadedBytes = (int) DB::table('testimonials')
            ->where('created_at', '>=', $monthStart)
            ->sum('file_size_bytes');

        return [
            'total_videos' => $totalVideos,
            'pending_videos' => (int) DB::table('testimonials')->whereIn('status', ['uploaded', 'pending_review'])->count(),
            'active_campaigns' => (int) DB::table('campaigns')->where('status', 'active')->count(),
            'monthly_upload_mb' => round($uploadedBytes / (1024 * 1024), 2),
            'total_campaigns' => $totalCampaigns,
            'show_onboarding' => $totalVideos === 0 || $totalCampaigns === 0,
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => $this->stats(),
        ]);
    }
}
