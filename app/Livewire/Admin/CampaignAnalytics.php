<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CampaignAnalytics extends Component
{
    public string $campaignId;

    public function mount(string $campaignId): void
    {
        $this->campaignId = $campaignId;
    }

    public function render()
    {
        $campaign = DB::table('campaigns')->select('id', 'name')->where('id', $this->campaignId)->first();

        $series = DB::table('testimonials')
            ->select('id', 'author_name', 'views_count', 'click_count')
            ->where('campaign_id', $this->campaignId)
            ->whereNull('deleted_at')
            ->orderByDesc('views_count')
            ->limit(8)
            ->get();

        $totalViews = (int) $series->sum('views_count');
        $totalClicks = (int) $series->sum('click_count');
        $ctr = $totalViews > 0 ? round(($totalClicks / $totalViews) * 100, 2) : 0;

        return view('livewire.admin.campaign-analytics', [
            'campaign' => $campaign,
            'series' => $series,
            'totalViews' => $totalViews,
            'totalClicks' => $totalClicks,
            'ctr' => $ctr,
        ]);
    }
}
