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
    public $orders;
    public $lowStocks;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->branches = Branch::select('id')->get();

        $this->orders = Order::select('id')->where('order_status', 'pending')->get();

        $this->lowStocks = DB::select("CALL getLowOnStocks");
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dash-board');
    }
}
