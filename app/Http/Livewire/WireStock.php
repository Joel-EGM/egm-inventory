<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Stock;
use App\Models\ViewData;
use App\Http\Traits\ModalVariables;
use DB;
use PDF;

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

    public function generatePDF()
    {
        $stocks = $this->loadData($this->viewMode);

        $data = [
            'stocks' => $stocks
        ];

        $pdf = PDF::loadView('pdfFormat', $data)->output();

        return response()->streamDownload(fn () => print($pdf), 'stockreport_'.today()->toDateString().'.pdf');
    }
}
