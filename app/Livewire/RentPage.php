<?php

namespace App\Livewire;

use App\Models\CategoryRent;
use Livewire\Component;

class RentPage extends Component
{
    public $activeCategory = null;
    public $categories;
    public $rents;
    public $search = '';

    public function mount()
    {
        $this->categories = CategoryRent::all();
        $this->rents = $this->filterRents();
    }

    public function updatedSearch()
    {
        $this->rents = $this->filterRents();
    }

    public function filterByCategory($categoryId = null)
    {
        $this->activeCategory = $categoryId;
        $this->rents = $this->filterRents();
    }

    protected function filterRents()
    {
        $query = \App\Models\Rent::query();

        if ($this->activeCategory) {
            $query->where('category_id', $this->activeCategory);
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.rent-page');
    }
}
