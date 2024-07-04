<?php

namespace App\Exports;

use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MonthlyReport implements FromView, WithEvents
{
    protected $test;
    protected $yr;
    protected $mons;
    public function __construct($test)
    {

        $explode = explode('-', $test);
        $this->yr = $explode[0];
        $this->mons = $explode[1];

    }

    public function view(): View
    {
        $findData = DB::select("CALL getMonthlyReport($this->yr,$this->mons)");

        $collect = collect($findData)->groupby('branch_name');
        return view('partials.monthreportexcel', [
            'monthlyreport' => $collect
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {

                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);


            },
        ];
    }
}
