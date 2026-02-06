<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    /**
     * @return array<string, int>
     */
    public function stats(): array
    {
        return [
            'total_videos' => (int) DB::table('testimonials')->count(),
            'pending_videos' => (int) DB::table('testimonials')->whereIn('status', ['uploaded', 'pending_review'])->count(),
            'active_campaigns' => (int) DB::table('campaigns')->where('status', 'active')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => $this->stats(),
        ]);
    }
}
