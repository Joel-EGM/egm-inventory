<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use PDF;
use DB;

class ExportDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(OrderDetail $detail)
    {
    }

    public function generatePDF()
    {
        // dd(last(request()->path()));
        // $currentURL = url()->current();
        // $explodeURL = explode('/', $currentURL);

        $getID = last(request()->segments());

        $stock = DB::select("CALL getOrderDetails($getID)");

        $data = [
            'stocks' => $stock
        ];
        // dd($data);
        $pdf = PDF::loadView('pdfFormat', $data);

        return $pdf->stream('stocksreport_'.today()->toDateString().'.pdf');
    }
}
