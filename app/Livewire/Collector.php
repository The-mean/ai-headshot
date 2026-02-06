<?php

namespace App\Livewire;

use Livewire\Component;

class Collector extends Component
{
    public string $campaignId;

    public function mount(string $campaignId): void
    {
        $this->campaignId = $campaignId;
    }

    public function render()
    {
        return view('livewire.collector');
    }
}
