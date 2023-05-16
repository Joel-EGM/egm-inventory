<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Stock;
use App\Models\ViewData;
use App\Http\Traits\ModalVariables;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\WithPagination;

class WireStock extends Component
{
    use ModalVariables;
    use WithPagination;

    public $stocks;

    public function mount()
    {
        $this->loadData($this->viewMode);
    }

    public function render()
    {
        return view('livewire.stock')->with('stockitems', $this->stocks->paginateArray(10));
    }

    public function updatedViewMode()
    {
        $this->loadData($this->viewMode);
    }

    private function loadData($mode)
    {
        $id = (int) $mode;
        $this->stocks = collect(DB::select("CALL getData($id)"))
        ->map(function ($items) {
            if($this->viewMode != 1) {
                return[
                    'item_id' =>$items->item_id,
                    'created_at' =>$items->created_at,
                    'item_name' =>$items->item_name,
                    'totalqty' =>$items->totalqty
                ];
            } else {
                return[
                    'item_id' =>$items->item_id,
                    'created_at' =>$items->created_at,
                    'item_name' =>$items->item_name,
                    'unit_name' =>$items->unit_name,
                    'totalqtyWHOLE' =>$items->totalqtyWHOLE,
                    'totalqtyREMAINDER' =>$items->totalqtyREMAINDER
                ];
            }
        });

    }
}
