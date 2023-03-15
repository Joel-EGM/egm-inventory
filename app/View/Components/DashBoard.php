<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\Branch;
use App\Models\Order;

class DashBoard extends Component
{
    public $branches;
    public $orders;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->branches = Branch::select('id')->get();
        $this->orders = Order::select('id')->where('order_status', 'pending')->get();
    }

    // public function mount()
    // {
    //     $this->branches = Branch::all();
    // }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dash-board');
    }
}
