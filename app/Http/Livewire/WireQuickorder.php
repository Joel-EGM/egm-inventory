<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\OrderDetail;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class WireQuickorder extends Component
{
    use ModalVariables;
    use WireVariables;

    public $getBySupplier;
    public $total_amount;
    public $itemID;
    public $sup_id;
    public $arrayItemId = [];
    public $arrayOrderQty = [];
    public $arrayUnitPrice = [];
    public $arrayTotalAmt = [];
    public $key;
    public $selectedSupplier;

    protected $listeners = ['submitQuickOrder'];

    protected function messages()
    {
        return[
            'arrayItemId.required' => 'No changes were detected.'
        ];
    }

    public function mount()
    {
        $this->orders = Order::all();
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

        $stockBySupplier = collect($this->lowStocks)
            ->where('supplier_id', $this->selectedSupplier)
            ->all();

        $this->itemID = collect($stockBySupplier)->pluck('item_id');
        $this->sup_id = collect($stockBySupplier)->pluck('supplier_id');

        $orderBySupplier = OrderDetail::select(
            'order_details.id',
            'supplier_id',
            'suppliers_name',
            'order_details.item_id',
            'item_name',
            'order_details.quantity',
            'order_type',
            'order_details.price',
            'total_amount'
        )
            ->selectRaw('sum(stocks.quantity) as quantity', )
            ->join('items', 'items.id', '=', 'order_details.item_id')
            ->join('suppliers', 'suppliers.id', '=', 'order_details.supplier_id')
            ->join('stocks', 'stocks.item_id', '=', 'order_details.item_id')
            ->whereIn('order_details.item_id', $this->itemID)
            ->whereIn('order_details.supplier_id', $this->sup_id)
            ->whereRaw('order_details.deleted_at is null')
            ->groupBy(
                'order_details.id',
                'supplier_id',
                'suppliers_name',
                'order_details.item_id',
                'item_name',
                'order_details.quantity',
                'order_type',
                'order_details.price',
                'total_amount'
            )
            ->orderBy('order_details.created_at')
            ->get();

        $mapped = $orderBySupplier->map(function ($mapData) {
            return[
            'id' => $mapData->id,
            'supplier_id' => $mapData->supplier_id,
            'suppliers_name' => $mapData->suppliers_name,
            'quantity' => $mapData->quantity,
            'item_id' => $mapData->item_id,
            'item_name' => $mapData->item_name,
            'order_type' => $mapData->order_type,
            'reorder_level' => $mapData->reorder_level,
            'price' => $mapData->price,
            'total' => $mapData->total,
            'total_amount' => $mapData->total_amount,
            ];
        });

        $unique = $mapped->unique('item_id');
        $this->getBySupplier = $unique->values()->all();
        // ---------------------------------------------
        //LOAD PRICE
        $arrayItemID = $this->itemID->toArray();
        $this->unitName = Item::whereIn('id', $arrayItemID)->get();
        if ($this->unitName->pluck('fixed_unit')->first() === 1) {
            $this->unitString = "Unit";

            $this->unitPriceID = ItemPrice::whereIn('item_id', $arrayItemID)
                ->whereIn('supplier_id', $this->sup_id)
                ->first();

            $this->loadPrice();
        }
    }

    public function render()
    {
        return view('livewire.wire-quickorder');
    }


    public function submitQuickOrder()
    {
        if(empty($this->arrayItemId)) {
            $validatedData = $this->validate([
                'arrayItemId' => 'required',
               ]);
        }

        $orders = Order::create([
             'branch_id' => 1,

             'order_date' => Carbon::now()->format('Y-m-d'),

             'order_status' => 'pending',

             'or_number' => 0,

             'created_by' => Auth()->user()->name,
         ]);

        $arrayData = [];

        foreach ($this->getBySupplier as $key => $orderArray) {

            // $filtered = Arr::except($orderArray, empty('order_type'));

            array_push($arrayData, [
                'order_id' => $orders->id,

                'supplier_id' => $orderArray['supplier_id'],

                'item_id' => $orderArray['item_id'],

                'order_type' => preg_replace('/[^A-Za-z]/', '', $this->arrayItemId[$key]),

                'price' => $this->arrayUnitPrice[$key],

                'quantity' => $this->arrayOrderQty[$key],

                'total_amount' => $this->arrayTotalAmt[$key],

                'order_status' => 'pending',

                'is_received'   => 0,
            ]);
        }
        $this->orders->push($orders);

        OrderDetail::insert($arrayData);

        $this->clearForm();
        $this->clearFormVariables();

    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'formTitle',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'branch_id',
            'item_id',
            'supplier_id',
            'arrayItemId',
            'unitName',
            'unit_id',
            'arrayOrderQty',
            'arrayUnitPrice',
            'itemList',
            'arrayTotalAmt',
        ]);
    }



    public function loadPrice()
    {
        $unitPrice = ItemPrice::whereIn('item_id', $this->itemID)
            ->whereIn('supplier_id', $this->sup_id)
            ->first();

        $this->unitPrice = $unitPrice->price_perUnit;
    }

    public function updatedArrayItemId($value, $key)
    {
        $this->key = $key;

        $explodeResult = explode(' ', $value);

        $unitId = (int) $explodeResult[0];
        $this->unitString = $explodeResult[1];

        $this->unitPriceID = ItemPrice::where('item_id', (int) $unitId)
            ->whereIn('supplier_id', $this->sup_id)
            ->first();

        $this->arrayUnitPrice[$key] = $this->unitPriceID->price_perPieces;



        if ($this->unitString === "Unit") {
            $this->arrayUnitPrice[$key] = $this->unitPriceID->price_perUnit;
        }

        if (isset($this->arrayOrderQty[$key]) && $this->arrayOrderQty[$key] > 0) {
            $this->computeTotalAmount();
        }
    }

    public function updatedArrayOrderQty()
    {
        $this->computeTotalAmount();
    }

    private function computeTotalAmount()
    {
        $this->arrayTotalAmt[$this->key]
        = number_format(
            (int) $this->arrayOrderQty[$this->key] * $this->arrayUnitPrice[$this->key],
            2,
            '.',
            ''
        );
    }

    public function removeItem($index)
    {
        unset($this->getBySupplier[$index]);
        $this->getBySupplier = array_values($this->getBySupplier);
    }
}
