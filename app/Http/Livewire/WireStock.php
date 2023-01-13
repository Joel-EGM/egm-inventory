<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Stock;

class WireStock extends Component
{
    public $stocks;

    public function mount()
    {
        $this->stocks = Stock::all();
    }

    public function render()
    {
        return view('livewire.stock');
    }
}
