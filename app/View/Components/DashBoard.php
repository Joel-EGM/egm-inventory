<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Item;
use App\Models\OrderDetail;

class DashBoard extends Component
{
    public $branches;
    public $HO2Supplier;
    public $branch2HO;
    public $lowStocks;

    public function __construct()
    {
        $this->branches = Branch::select('status')->where('status', 1)->count();

        $this->HO2Supplier = collect(DB::select("CALL getPendingPO(1)"));

        $this->branch2HO = collect(DB::select("CALL getPendingPO(2)"));

        $this->lowStocks = collect(DB::select("CALL getLowOnStocks"));

    }

    public function render(): View|Closure|string
    {

        $groups = OrderDetail::with('items')
        ->select(
            'item_name',
        )
        ->selectRaw('count(*) as total')
        ->join('items', 'items.id', '=', 'order_details.item_id')
        ->groupBy('item_name')
        ->pluck('total', 'item_name')
        ->all();

        for ($i=0; $i<=count($groups); $i++) {
            $colours[] = '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6);
        }

        $chart = new OrderDetail();
        $chart->labels = (array_keys($groups));
        $chart->dataset = (array_values($groups));
        $chart->colours = $colours;

        return view('components.dash-board', compact('chart'));
    }

    public function charts()
    {   

    }
}
