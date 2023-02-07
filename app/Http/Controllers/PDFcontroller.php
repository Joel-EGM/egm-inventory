<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Http\Traits\ModalVariables;
use PDF;
use DB;

class PDFcontroller extends Controller
{
    use ModalVariables;

    public function generatePDF()
    {
        dd($this->viewMode);

        $stocks = DB::select("CALL getData(1)");

        $data = [
            'stocks' => $stocks
        ];

        $pdf = PDF::loadView('pdfFormat', $data);

        return $pdf->download('stockreport_'.today()->toDateString().'.pdf');
    }
}
