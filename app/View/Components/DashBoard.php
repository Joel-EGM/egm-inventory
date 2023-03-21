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

class DashBoard extends Component
{
    public $branches;
    public $HO2Supplier;
    public $branch2HO;
    public $lowStocks;

    public function __construct()
    {
        $this->branches = Branch::select('id')->get();

        $this->HO2Supplier = collect(DB::select("CALL getPendingPO(1)"));

        $this->branch2HO = collect(DB::select("CALL getPendingPO(2)"));

        $this->lowStocks = collect(DB::select("CALL getLowOnStocks"));
    }

    public function render(): View|Closure|string
    {
        return view('components.dash-board');
    }
}
