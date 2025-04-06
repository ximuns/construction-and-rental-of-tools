<?php

namespace App\Livewire;

use Livewire\Component;

class Contact extends Component
{
    public function index()
    {
        return \App\Models\Contact::all();
    }

    public function render()
    {
        return view('livewire.contact', [
            'contacts' => $this->index(),
        ]);
    }
}
