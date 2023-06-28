<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Item;
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
    public $getBySupplier;
    public $total_amount;
    public $chart;

    public function mount()
    {

        $this->orders = Order::all();
        $this->order_details = OrderDetail::all();

        $this->branches = Branch::select('status')->where('status', 1)->count();

        $this->HO2Supplier = collect(DB::select("CALL getPendingPO(1)"));

        $this->branch2HO = collect(DB::select("CALL getPendingPO(2)"));

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
        $this->radioSupplier = $this->lowStocks->whereIn('supplier_id', $sup_id)->unique('supplier_id');
        $this->radioSupplier->values()->all();


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

        $this->chart = new OrderDetail();
        $this->chart->labels = (array_keys($groups));
        $this->chart->dataset = (array_values($groups));
        $this->chart->colours = $colours;
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

        $orders = Order::create([

             'branch_id' => 1,

             'order_date' => Carbon::now()->format('Y-m-d'),

             'order_status' => 'pending',

             'or_number' => 0,

             'created_by' => Auth()->user()->name,


         ]);

        foreach ($this->getBySupplier as $orderArray) {
            OrderDetail::create([

                'order_id' => $orders->id,

                'supplier_id' => $orderArray['supplier_id'],

                'item_id' => $orderArray['item_id'],

                'price' => $orderArray['price'],

                'quantity' => $orderArray['quantity'],

                'total_amount' => $orderArray['total_amount'],

                'order_type' => $orderArray['order_type'],

                'order_status' => 'pending',

                'is_received'   => 0,


            ]);


            $this->orders->push();
            $this->modalToggle();

            $notificationMessage = 'Order successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        }
    }

    public function quickOrder($formAction = null)
    {
        $stockBySupplier = collect($this->lowStocks)->where('supplier_id', $this->selectedSupplier)->all();
        $itemID = collect($stockBySupplier)->pluck('item_id');
        $sup_id = collect($stockBySupplier)->pluck('supplier_id');

        $orderBySupplier = OrderDetail::with('suppliers', 'items')
        ->select('supplier_id', 'item_id', 'quantity', 'order_type', 'price', 'total_amount')
        ->whereIn('item_id', $itemID)
        ->whereIn('supplier_id', $sup_id)
        ->whereRaw('deleted_at is null')
        ->orderBy('created_at')
        ->get();

        // dd($orderBySupplier);
        $unique = $orderBySupplier->unique('item_id');
        $this->getBySupplier = $unique->values()->all();

        if (!$formAction) {
            $this->formTitle = 'Quick Order';
            $this->isFormOpen = true;
        }
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

    //LOAD PRICE\\
    public function updatedItemId()
    {
        $this->reset([
                'quantity',
                'total_amount',
                'inStocks',
        ]);
        $this->unitName = Item::where('id', (int) $this->item_id)->get();
        $this->inStocks = $this->stocks->where('item_id', $this->item_id)->sum('quantity');

        if ($this->unitName->pluck('fixed_unit')->first() === 1) {
            $this->unitString = "Unit";
            $this->inStocks = $this->stocks->where('item_id', $this->item_id)->sum('quantity');
            $this->unitPriceID = $this->userBranch != 1 ? ItemPrice::where('item_id', $this->item_id)->first() : ItemPrice::where('item_id', $this->item_id)->where('supplier_id', $this->supplier_id)->first();
            $this->loadPrice();
        }

    }

    public function loadPrice()
    {
        if($this->userBranch != 1) {
            $unitPrice = ItemPrice::where('item_id', (int) $this->item_id)->min('price_perUnit');
            $this->unitPrice = $unitPrice;

        } else {
            $unitPrice = ItemPrice::where('item_id', (int) $this->item_id)->where('supplier_id', $this->supplier_id)->first();
            $this->unitPrice = $unitPrice->price_perUnit;
        }

    }

    public function updatedQuantity()
    {
        $this->computeTotalAmount();
    }

    private function computeTotalAmount()
    {
        $this->total_amount = number_format((int) $this->quantity * $this->unitPrice, 2, '.', '');
    }
}
