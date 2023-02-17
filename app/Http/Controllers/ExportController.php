<?php

namespace App\Http\Controllers;

use App\Exports\StocksExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock;
use PDF;
use DB;

class ExportController extends Controller
{
    public function generatePDF()
    {
        // dd(last(request()->path()));
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

    public function export()
    {
        return Excel::download(new StocksExport(), 'stocksreport.xlsx');
    }
}
