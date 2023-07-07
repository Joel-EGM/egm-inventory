<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use DB;

class WireChart extends Component
{
    public function render()
    {

        $groups = Order::with('branches')
        ->select(
            'branch_name',
        )
        ->selectRaw('count(*) as total')
        ->join('branches', 'branches.id', '=', 'orders.branch_id')
        ->groupBy('branch_name')
        ->pluck('total', 'branch_name')
        ->all();

        for ($i=0; $i<=count($groups); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }

        $charts = new Order();
        $charts->labels = (array_keys($groups));
        $charts->dataset = (array_values($groups));
        $charts->colours = $colours;

        return view('livewire.chart', compact('charts'));
    }
}
