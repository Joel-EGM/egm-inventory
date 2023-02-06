<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use PDF;

class PDFcontroller extends Controller
{
    public function generatePDF()
    {
        $stocks = Stock::get();

        $data = [
            'stocks' => $stocks
        ];

        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download('stockreport_'.today()->toDateString().'.pdf');
    }
}
