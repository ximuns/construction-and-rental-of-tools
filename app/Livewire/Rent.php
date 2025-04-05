<?php

namespace App\Livewire;

use Livewire\Component;

class Rent extends Component
{
    public function index()
    {
        return \App\Models\Rent::where('is_active', true)->get();
    }
    public function render()
    {
        return view('livewire.rent', [
            'rents' => $this->index(),
        ]);
    }
}
