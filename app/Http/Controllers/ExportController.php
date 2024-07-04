<?php

namespace App\Http\Controllers;

use App\Exports\StocksExport;
use App\Exports\MonthlyReport;
use App\Exports\CSVExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __invoke(Stock $stock)
    {
    }

    public function generatePDF()
    {
        $getID = last(request()->segments());
        $branch_id = Auth()->user()->branch_id;
        $stock = DB::select("CALL getData($getID,$branch_id)");

        $data = [
            'stocks' => $stock
        ];

        if($getID != "1") {
            $pdf = PDF::loadView('pdfFormat2', $data);
        } else {
            $pdf = PDF::loadView('pdfFormat', $data);
        }

        return $pdf->stream('stocksreport_' . today()->toDateString() . '_' . Auth()->user()->name . '.pdf');
    }

    public function export()
    {
        return Excel::download(new StocksExport(), 'stocksreport_' . today()->toDateString() . '.xlsx');
    }

    public function monthlyreport($mos)
    {
        return Excel::download(new MonthlyReport($mos), 'monthlyreport_' . today()->toDateString() . '.xlsx');
    }


    public function csv($mos)
    {
        return Excel::download(new CSVExport($mos), 'csvreport_' . today()->toDateString() . '.csv');
    }

}
