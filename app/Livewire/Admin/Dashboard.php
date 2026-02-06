<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public string $analyticsCampaignId = '';

    public function mount(): void
    {
        if ($this->analyticsCampaignId === '') {
            $this->analyticsCampaignId = (string) (DB::table('campaigns')->value('id') ?? '');
        }
    }

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
        $campaigns = DB::table('campaigns')->select('id', 'name')->orderByDesc('created_at')->limit(20)->get();

        $series = collect();
        $totals = [
            'views' => 0,
            'clicks' => 0,
            'ctr' => 0.0,
        ];

        if ($this->analyticsCampaignId !== '') {
            $series = DB::table('testimonials')
                ->select('id', 'author_name', 'views_count', 'click_count')
                ->where('campaign_id', $this->analyticsCampaignId)
                ->whereNull('deleted_at')
                ->orderByDesc('views_count')
                ->limit(8)
                ->get();

            $totals['views'] = (int) $series->sum('views_count');
            $totals['clicks'] = (int) $series->sum('click_count');
            $totals['ctr'] = $totals['views'] > 0 ? round(($totals['clicks'] / $totals['views']) * 100, 2) : 0;
        }

        return view('livewire.admin.dashboard', [
            'stats' => $this->stats(),
            'campaigns' => $campaigns,
            'series' => $series,
            'analyticsTotals' => $totals,
        ]);
    }
}
