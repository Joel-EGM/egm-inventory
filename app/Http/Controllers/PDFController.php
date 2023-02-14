<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use PDF;
use DB;

class PDFController extends Controller
{
    public function generatePDF()
    {
        // $currentURL = url()->current();
        // $explodeURL = explode('/', $currentURL);
        $getID = last(request()->segments());

        $stock = DB::select("CALL getData($getID)");

        $data = [
            'stocks' => $stock
        ];

        $pdf = PDF::loadView('pdfFormat', $data);

        return $pdf->stream('stocksreport_'.today()->toDateString().'.pdf');
    }
}
