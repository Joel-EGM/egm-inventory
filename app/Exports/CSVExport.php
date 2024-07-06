<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CSVExport implements FromArray, WithMapping, WithHeadings
{
    use Exportable;

    protected $test;
    protected $yr;
    protected $mons;

    public $collect;

    public function __construct($test)
    {

        $explode = explode('-', $test);
        $this->yr = $explode[0];
        $this->mons = $explode[1];

        $this->arrayData = DB::select("CALL getMonthlyReport($this->yr,$this->mons)");


    }

    public function headings(): array
    {
        return [
            'Date',
            'Reference',
            'Date Clear in Bank Rec',
            'Number of Distributions',
            'G/L Account',
            'Description',
            'Amount',
            'Job ID',
            'Used for Reimbursable Expenses',
            'Transaction Period',
            'Transaction Number',
            'Recur Number',
            'Recur Frequency'
        ];
    }

    public function map($monthly_report): array
    {
        return [
            $monthly_report['a0'],
            $monthly_report['b0'],
            $monthly_report['c0'],
            $monthly_report['d0'],
            $monthly_report['e0'],
            $monthly_report['f0'],
            $monthly_report['g0'],
            $monthly_report['h0'],
            $monthly_report['i0'],
            $monthly_report['j0'],
            $monthly_report['k0'],
            $monthly_report['l0'],
            $monthly_report['m0'],
        ];
    }


    public function array(): array
    {
        $total_HO = 0;
        $GL_id_HO = 6450;
        $monthly_report = array();
        $total_branch = 0;
        $GL_total = 1201;
        $branch_name = '';
        $description = 'Supplies Used/Req';
        $total_rows = collect($this->arrayData)
        ->where('branch_name','!=', 'Head Office Emp')
        ->where('branch_name','!=', 'Ho Sattelite Emp')
        ->pluck('branch_name')->unique()->count() + count($this->arrayData);

        foreach($this->arrayData as $key => $data) {
            if(! in_array($data->branch_name, ['Ho Sattelite Emp', 'Head Office Emp'])) {
                if ($branch_name === '') {
                    $branch_name = $data->branch_name;
                }

                if ($branch_name != $data->branch_name) {
                    $report_data = array(

                        'a0' => $data->lastday,
                        'b0' => $description,
                        'c0' => '',
                        'd0' => $total_rows,
                        'e0' => $GL_total,
                        'f0' => strtoupper($branch_name),
                        'g0' => -$total_branch,
                        'h0' => '',
                        'i0' => 'FALSE',
                        'j0' => $data->month,
                        'k0' => $data->day,
                        'l0' => '0',
                        'm0' => '0',
                    );

                    array_push($monthly_report, $report_data);

                    $total_branch = 0;

                    $branch_name = $data->branch_name;
                    $total_branch = $data->Total;
                } else {
                    $total_branch += $data->Total;
                }

                $report_data = array(

                    'a0' => $data->lastday,
                    'b0' => $description,
                    'c0' => '',
                    'd0' => $total_rows,
                    'e0' => $data->acc_number.'-A',
                    'f0' => 'OR # '.$data->or_number,
                    'g0' => $data->Total,
                    'h0' => '',
                    'i0' => 'FALSE',
                    'j0' => $data->month,
                    'k0' => $data->day,
                    'l0' => '0',
                    'm0' => '0',

                );

                array_push($monthly_report, $report_data);
            } else {
                $total_HO += $data->Total;
            }

        }

        $report_data = array(

            'a0' => $data->lastday,
            'b0' => $description,
            'c0' => '',
            'd0' => $total_rows,
            'e0' => $GL_total,
            'f0' => strtoupper($branch_name),
            'g0' => -$total_branch,
            'h0' => '',
            'i0' => 'FALSE',
            'j0' => $data->month,
            'k0' => $data->day,
            'l0' => '0',
            'm0' => '0',
        );

        array_push($monthly_report, $report_data);

        if($total_HO > 0) {
            $report_data = array(

                'a0' => $data->lastday,
                'b0' => $description,
                'c0' => '',
                'd0' => $total_rows,
                'e0' => $GL_id_HO,
                'f0' => 'HEAD OFFICE SUPPLIES '. $data->MonthName .' '.$data->Year,
                'g0' => $total_HO,
                'h0' => '',
                'i0' => 'FALSE',
                'j0' => $data->month,
                'k0' => $data->day,
                'l0' => '0',
                'm0' => '0',

            );

            array_push($monthly_report, $report_data);

            $report_data = array(

                'a0' => $data->lastday,
                'b0' => $description,
                'c0' => '',
                'd0' => $total_rows,
                'e0' => $GL_total,
                'f0' => 'HEAD OFFICE',
                'g0' => -$total_HO,
                'h0' => '',
                'i0' => 'FALSE',
                'j0' => $data->month,
                'k0' => $data->day,
                'l0' => '0',
                'm0' => '0',

            );


            array_push($monthly_report, $report_data);

        }
        return $monthly_report;
    }
}
