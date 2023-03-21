<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Stock;
use App\Models\ViewData;
use App\Http\Traits\ModalVariables;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class WireStock extends Component
{
    use ModalVariables;

    public $stocks;


    public function mount()
    {
        $this->loadData($this->viewMode);
    }

    public function render()
    {
        return view('livewire.stock');
    }

    public function updatedViewMode()
    {
        $this->loadData($this->viewMode);
    }

    private function loadData($mode)
    {
        $id = (int) $mode;
        $this->stocks= DB::select("CALL getData($id)");
    }
}
