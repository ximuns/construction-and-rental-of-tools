<?php

namespace App\Livewire;

use App\Models\SocialNetworck;
use Livewire\Component;

class SocialNetwork extends Component
{
    public function index()
    {
        return SocialNetworck::all();
    }
    public function render()
    {
        return view('livewire.social-network', [
            'socialNetworks' => $this->index(),
        ]);
    }
}
