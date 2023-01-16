<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Stock;
use App\Models\ViewData;

class WireStock extends Component
{
    public $stocks;

    public function mount()
    {
        $this->stocks = ViewData::all();
        // dd($this->stocks);
    }

    public function render()
    {
        return view('livewire.stock');
    }
}
