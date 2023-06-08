<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use DB;

class WireChart extends Component
{
    public function render()
    {
        //this year
        // $thisyear = Order::query()
        // ->whereYear('order_date', date('Y'))
        // ->selectRaw('SUBSTRING(MONTHNAME(order_date),1,3) as month')
        // ->selectRaw('MONTH(order_date) as month2')
        // ->selectRaw('count(*) as count')
        // ->groupByRaw('month, month2')
        // ->orderByRaw('month2')
        // ->pluck('count', 'month')
        // ->all();


        //last year
        // $lastyear = Order::query()
        // ->whereYear('order_date', date('Y') - 1)
        // ->selectRaw('SUBSTRING(MONTHNAME(order_date),1,3) as month')
        // ->selectRaw('MONTH(order_date) as month2')
        // ->selectRaw('count(*) as count')
        // ->groupByRaw('month, month2')
        // ->orderByRaw('month2')
        // ->pluck('count', 'month')
        // ->all();



        $groups = Order::with('branches')
        ->select(
            'branch_name',
        )
        ->selectRaw('count(*) as total')
        ->join('branches', 'branches.id', '=', 'orders.branch_id')
        ->groupBy('branch_name')
        ->pluck('total', 'branch_name')
        ->all();


        // $lastyearKey = (array_keys($lastyear));
        // $lastyearValue = (array_values($lastyear));

        // $thisyearKey = (array_keys($thisyear));
        // $thisyearValue = (array_values($thisyear));



        for ($i=0; $i<=count($groups); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }

        // $chart = new Order();
        // $chart->labels = (array_keys($lastyear));
        // $chart->dataset = (array_values($lastyear));
        // $chart->colours = $colours;
        $charts = new Order();
        $charts->labels = (array_keys($groups));
        $charts->dataset = (array_values($groups));
        $charts->colours = $colours;

        return view('livewire.chart', compact('charts'));

    }

}
