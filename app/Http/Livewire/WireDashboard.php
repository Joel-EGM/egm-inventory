<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\OrderDetail;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use Livewire\WithPagination;
use Carbon\Carbon;

class WireDashboard extends Component
{
    use ModalVariables;
    use WireVariables;
    use WithPagination;

    public $branches;
    public $HO2Supplier;
    public $branch2HO;
    public $lowStocks;
    public $radioSupplier;
    public $lowCollection;
    public $selectedSupplier = 'All';
    public $chart;
    public $getBySupplier;

    public function mount()
    {
        $this->orders = Order::all();
        $this->order_details = OrderDetail::all();

        $this->branches = Branch::select('status')->where('status', 1)->count();

        $this->HO2Supplier = collect(DB::select("CALL getPendingPO(1)"));

        $this->branch2HO = collect(DB::select("CALL getPendingPO(2)"));

        $this->stocks = Stock::all();

        $this->lowCollection = collect(DB::select("CALL getLowOnStocks"));

        $this->lowStocks = $this->lowCollection->map(function ($low) {

            return[
                'supplier_id' => $low->supplier_id,
                'suppliers_name' => $low->suppliers_name,
                'item_id' => $low->item_id,
                'item_name' => $low->item_name,
                'reorder_level' => $low->reorder_level,
                'total' => $low->total,
            ];
        });

        $sup_id = $this->lowStocks->pluck('supplier_id');

        $this->radioSupplier = $this->lowStocks
            ->whereIn('supplier_id', $sup_id)
            ->unique('supplier_id');

        $this->radioSupplier->values()->all();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'allLowStocks' => $this->lowStocks->paginateArray(5)
        ]);
    }

    public function updatedSelectedSupplier()
    {

        $lowCollection = collect($this->lowCollection);

        $this->lowStocks = $lowCollection->filter(function ($low) {
            if($this->selectedSupplier === 'All') {
                return $low;
            }

            return $low['supplier_id'] === (int)$this->selectedSupplier;
        })->map(function ($low) {
            return[
                'supplier_id' => $low['supplier_id'],
                'suppliers_name' => $low['suppliers_name'],
                'item_id' => $low['item_id'],
                'item_name' => $low['item_name'],
                'reorder_level' => $low['reorder_level'],
                'total' => $low['total'],
            ];
        });
    }

    public function submit()
    {
        $this->emit('submitQuickOrder');
    }

    public function modalToggle($formAction = null)
    {
        if (!$formAction) {

            $this->formTitle = 'Quick Order';

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        }
    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'formTitle',
        ]);
    }
}
