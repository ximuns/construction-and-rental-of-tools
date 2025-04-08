<?php

namespace App\Livewire;

use Livewire\Component;

class Advantage extends Component
{
    public function index()
    {
        return \App\Models\Advantage::all();
    }
    public function render()
    {
        return view('livewire.advantage', [
            'advantages' => $this->index(),
        ]);
    }
}
