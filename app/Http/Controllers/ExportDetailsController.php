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

    public function generatePO()
    {
        // dd(last(request()->path()));
        // $currentURL = url()->current();
        // $explodeURL = explode('/', $currentURL);

        $getID = last(request()->segments());

        $findData = DB::select("CALL getOrderDetails($getID)");
        // dd($findData);
        $data = [
            'orderDetails' => $findData
        ];
        // dd($data);
        $pdf = PDF::loadView('orderpdf', $data);

        return $pdf->stream('PO_report_'.today()->toDateString().'.pdf');
    }
}
