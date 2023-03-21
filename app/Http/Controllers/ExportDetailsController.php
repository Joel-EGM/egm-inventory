<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ExportDetailsController extends Controller
{
    public function __invoke(OrderDetail $detail)
    {
    }

    public function generatePO()
    {
        $getID = last(request()->segments());

        $findData = DB::select("CALL getOrderDetails($getID)");

        $data = [
            'orderDetails' => $findData
        ];

        $pdf = PDF::loadView('orderpdf', $data);

        return $pdf->stream('PO_report_'.today()->toDateString().'.pdf');
    }
}
