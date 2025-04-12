<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;

class AdvantagesPage extends Component
{
    #[Computed]
    public function index()
    {
        return \App\Models\AdvantagesPage::all();
    }

    public function render()
    {
        return view('livewire.advantages-page', [
            'advantages' => $this->index(),
        ]);
    }
}
