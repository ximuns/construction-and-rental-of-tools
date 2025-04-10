<?php

namespace App\Livewire;

use App\Models\Service;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Services extends Component
{
    public $limit = 8;
    public $totalServices;

    public function mount()
    {
        $this->totalServices = Service::where('is_active', true)->count();
    }

    #[Computed]
    public function services()
    {
        return Service::where('is_active', true)
            ->limit($this->limit)
            ->get();
    }

    public function loadMore()
    {
        $this->limit += 8;
    }

    public function render()
    {
        return view('livewire.services', [
            'services' => $this->services,
            'showButton' => $this->limit < $this->totalServices,
        ]);
    }
}
