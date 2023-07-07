<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;

use App\Models\OrderDetail;

class Chart extends Component
{
    public function __construct()
    {

    }

    public function render(): View|Closure|string
    {
        //--------------CHART DATA--------------\\
        $groups = OrderDetail::with('items')
        ->select(
            'item_name',
        )
        ->selectRaw('count(*) as total')
        ->join('items', 'items.id', '=', 'order_details.item_id')
        ->groupBy('item_name')
        ->orderBy('total', 'desc')
        ->pluck('total', 'item_name')
        ->take(10)
        ->all();

        for ($i=0; $i<=count($groups); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }

        $chart = new OrderDetail();
        $chart->labels = (array_keys($groups));
        $chart->dataset = (array_values($groups));
        $chart->colours = $colours;

        return view('components.chart', compact('chart'));

    }
}
