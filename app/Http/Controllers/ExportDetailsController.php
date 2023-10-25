<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ExportDetailsController extends Controller
{
    public function __invoke(OrderDetail $detail) {}

    public function generatePO()
    {

        $getID = last(request()->segments());

        $findData = DB::select("CALL getOrderDetails($getID)");

        $data = [
            'orderDetails' => $findData
        ];
        // dd($data);

        $pdf = PDF::loadView('orderpdf', $data);
        // $customPaper = array(0,0,450,500);
        // $pdf->set_paper($customPaper);
        // $pdf->set_paper('letter', 'landscape');
        return $pdf->stream('order_report_' . today()->toDateString() . '.pdf');
    }
}
