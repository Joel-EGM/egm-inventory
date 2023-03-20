<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ModalVariables;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Item;

class DashBoard extends Component
{
    use ModalVariables;

    public $layoutTitle = 'Create Order';
    
    public $branches;
    public $HO2Supplier;
    public $branch2HO;
    public $lowStocks;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->branches = Branch::select('id')->get();

        $this->HO2Supplier = collect(DB::select("CALL getPendingPO(1)"));
        // dd($this->HO2Supplier);
        $this->branch2HO = collect(DB::select("CALL getPendingPO(2)"));
        // dd($orders);
        // $this->HO2Supplier = $orders->pluck('branch_id')->where('branch_id', '=', '1')->count();
        // dd($this->HO2Supplier);


        $this->lowStocks = collect(DB::select("CALL getLowOnStocks"));
    }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dash-board');
    }


    public function modalToggle($formAction = null)
    {
        if (!$formAction) {
            if ($this->Index === null) {
                $this->formTitle = 'Create Order';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }
}
