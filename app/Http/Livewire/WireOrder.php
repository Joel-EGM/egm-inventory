<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\OrderDetail;
use App\Models\Stock;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Traits\TrackDirtyProperties;
use App\Http\Interfaces\FieldValidationMessage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class WireOrder extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;

    public $layoutTitle = 'Create Order';
    public $oBranch;
    public $updateID;

    protected $rules = [
        'item_id' => 'bail|required',
        'order_date'  => 'bail|required|date',
        'supplier_id' => 'bail|required',
        'branch_id' => 'bail|required',
        'unitType' => 'bail|required',
        'quantity'  => 'required|numeric| max: 999',
        'unitPrice'  => 'bail|required|numeric',
        'total_amount'  => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->items = Item::all();

        $this->suppliers = Supplier::all();

        $this->branches = Branch::all();

        $this->order_details = OrderDetail::all();

        $this->stocks = Stock::all();

        $this->order_date = Carbon::now()->format('Y-m-d');

        $user = Auth()->user()->branch_id;

        if ($user != 1) {
            $this->orders = Order::where('branch_id', $user)->get();
        } else {
            $this->orders = Order::all();
        }


        $this->branchFind = Branch::where('id', $user)->first();
    }

    public function render()
    {
        return view('livewire.order');
    }

    public function submit()
    {
        $validatedItem = $this->validate();

        // logger($this->Index);
        if (is_null($this->Index)) {
            // logger("true part");

            $orders = Order::create([

                'branch_id' => $this->branch_id,

                'order_date' => $this->order_date,

                'order_status' => 'pending'

            ]);
            // dd($orders->id);
            foreach ($this->orderArrays as $orderArray) {
                $orders->orderDetails()->create([

                    'order_id' => $orders->id,

                    'supplier_id' => $orderArray['supplier_id'],

                    'item_id' => $orderArray['item_id'],

                    'unit_id' => $orderArray['unit_id'],

                    'price' => $orderArray['price'],

                    'quantity' => $orderArray['quantity'],

                    'total_amount' => $orderArray['total_amount'],

                    'order_status' => 'pending',

                    'is_received'   => 0,

                ]);
            }

            $this->orders->push($orders);
            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        }
    }

    public function orderUpdate()
    {
        // dd($this->updateID);
        // logger("false part");
        // $id = $this->orders->where('id', $this->Index)->first();
        // dd($id);
        // dd($this->Index);
        $orders = Order::where('id', $this->updateID)->update([

            'branch_id' => $this->branch_id,
        ]);

        // dd($this->orderArrays);
        $index = $this->updateID;
        // dd($index);
        foreach ($this->orderArrays as $orderArray) {
            OrderDetail::where('order_id', $this->updateID)->updateOrCreate([
                'order_id' => $this->updateID,

                'supplier_id' => $orderArray['supplier_id'],

                'item_id' => $orderArray['item_id'],

                'unit_id' => $orderArray['unit_id'],

                'price' => $orderArray['price'],

                'quantity' => $orderArray['quantity'],

                'total_amount' => $orderArray['total_amount'],

                'order_status' => 'pending',

                'is_received'   => 0,

            ]);
        }


        $this->orders->push();

        // $this->Index = null;
        $this->clearForm();

        $this->modalToggle();

        $notificationMessage = 'Record successfully updated.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);

    }

    public function addOrderArray()
    {
        $this->validate();

        $branchName = '';
        foreach ($this->branches as $branch) {
            if ($branch->id === (int) $this->branch_id) {
                $branchName = $branch->branch_name;
                break;
            }
        }

        $supplierName = '';
        foreach ($this->suppliers as $supplier) {
            if ($supplier->id === (int) $this->supplier_id) {
                $supplierName = $supplier->suppliers_name;
                break;
            }
        }

        $itemName = '';
        foreach ($this->items as $item) {
            if ($item->id === (int) $this->item_id) {
                $itemName = $item->item_name;
                break;
            }
        }

        $NameUnit = '';
        foreach ($this->items as $unit) {
            if ($unit->id === (int) $this->unitType) {
                $NameUnit = $unit->unit_name;
                break;
            }
        }


        array_push($this->orderArrays, [
        'id' => 0,
        'order_id' => 0,
        'order_date' => $this->order_date,

        'branch_id'  => $this->branch_id,
        'branch_name'  => $branchName,

        'supplier_id' => $this->supplier_id,
        'suppliers_name' => $supplierName,

        'item_id' => $this->item_id,
        'item_name'  => $itemName,

        'unit_id' => $this->unitType,
        'unit_name' => $NameUnit,

        'quantity' => $this->quantity,
        'price' => $this->unitPrice,
        'total_amount' => $this->total_amount,


        ]);
        // dd($this->orderArrays);
        $id = $this->unitPriceID['id'];

        $ipq = ItemPrice::whereId($id);


        if ($this->unitString === "Unit") {
            $price = $this->unitPriceID->price_perUnit;

            if ($this->unitPrice != $price) {
                $ipq->update(['price_perUnit' => $this->unitPrice,]);
            }
        } else {
            $price = $this->unitPriceID->price_perPieces;

            if ($this->unitPrice != $price) {
                $ipq->update(['price_perPieces' => $this->unitPrice,]);
            }
        }
    }

    public function removeItem($index)
    {
        // dd($this->orderArrays[$index]['order_id']);
        unset($this->orderArrays[$index]);

    }

    public function deleteItem($index)
    {
        // OrderDetail::destroy($index);
        $id = $this->orderArrays[$index]['id'];
        OrderDetail::find($id)->delete();
        unset($this->orderArrays[$index]);

    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'isDeleteOpen',
            'Index',
            'formTitle',
            'branch_id',
            'item_id',
            'supplier_id',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'branch_id',
            'item_id',
            'supplier_id',
            'unitType',
            'unit_id',
            'quantity',
            'unitPrice',
            'total_amount',
            'orderArrays',
        ]);
    }
    public function edit($id, $formAction = null)
    {
        // dd($this->Index);
        $this->updateID = $id;
        // dd($this->updateID);
        $this->order_date = $this->orders->where('id', $id)->pluck('order_date')->first();

        $this->branch_id = $this->orders->where('id', $id)->pluck('branch_id')->first();
        // dd($this->branch_id);
        $this->orderArrays = OrderDetail::with('branches', 'items', 'orders', 'suppliers')
        ->select(
            'order_details.id',
            'order_id',
            'branch_id',
            'branch_name',
            'order_date',
            'supplier_id',
            'suppliers_name',
            'item_id',
            'item_name',
            'unit_id',
            'unit_name',
            'quantity',
            'price',
            'total_amount'
        )
        ->where('order_id', $id)
        ->join('orders', 'orders.id', '=', 'order_details.order_id')
        ->join('branches', 'branches.id', '=', 'orders.branch_id')
        ->join('suppliers', 'suppliers.id', '=', 'order_details.supplier_id')
        ->join('items', 'items.id', '=', 'order_details.item_id')
        ->get()
        ->toArray();

        if (!$formAction) {
            $this->formTitle = 'Edit Order';
            $this->isFormOpen = true;
        }

        // $this->clearForm();
    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;

        $this->order_date = $this->orders[$this->Index]['order_date'];

        $this->branch_id = $this->orders[$this->Index]['branch_id'];

        $this->supplier_id = $this->order_details[$this->Index]['supplier_id'];

        $this->item_id = $this->order_details[$this->Index]['item_id'];

        $this->unitType = $this->order_details[$this->Index]['unit_name'];

        $this->quantity = $this->order_details[$this->Index]['quantity'];

        $this->unitPrice = $this->order_details[$this->Index]['price'];

        $this->total_amount = $this->order_details[$this->Index]['total_amount'];
        // dd($this->total_amount);
        // $this->total_amount = $this->order_details[$this->Index]['total_amount'];
        // $this->orderArrays = OrderDetail::where('id', $this->Index)->get();


        if ($formAction) {
            $this->formTitle = 'Delete Order';
            $this->isDeleteOpen = true;
        }
    }

    private function getOrderInfo($id)
    {
        $this->details = OrderDetail::where('order_id', $id)->get();
        $this->getOrderID = Order::where('id', $id)->pluck('id');
        $this->getBranchID = Order::where('id', $id)->pluck('branch_id')->first();
    }

    public function viewOrderDetails($id, $formAction = null)
    {
        $this->getOrderInfo($id);
        if (!$formAction) {
            $this->formTitle = 'Order Details';
            $this->isFormOpen = true;
        }
    }

    public function viewDetails($id, $formAction = null)
    {
        $this->getOrderInfo($id);
        if (!$formAction) {
            $this->formTitle = 'View Details';
            $this->isFormOpen = true;
        }
    }

    public function saveMethod()
    {
        if ($this->getBranchID != 1) {
            $this->subsctractBranchOrder();
        } else {
            $this->saveCheckedItems();
        }

        $this->reset(['selectedRecord']);
    }

    private function saveCheckedItems()
    {
        $getid = OrderDetail::whereIn('id', $this->selectedRecord)->get()->toArray();
        $getDataArray = collect($getid);
        $getSelectedItem = $getDataArray->pluck('item_id')->first();
        $getQuantity = $this->items->where('id', $getSelectedItem)->pluck('pieces_perUnit')->first();
        $checkFixedUnit = $this->items->where('id', $getSelectedItem)->pluck('fixed_unit')->first();
        if ($checkFixedUnit === 1) {
            foreach ($getid as $detail) {
                Stock::create([

                    'order_id' => $detail['order_id'],

                    'item_id' => $detail['item_id'],

                    'quantity' => $detail['quantity'],

                    'price' => $detail['price'],

                ]);
            }
        } else {
            foreach ($getid as $detail) {
                Stock::create([

                    'order_id' => $detail['order_id'],

                    'item_id' => $detail['item_id'],

                    'quantity' => $detail['quantity'] * $getQuantity,

                    'price' => $detail['price'],

                ]);
            }
        }


        OrderDetail::whereIn('id', $this->selectedRecord)->update([
            'order_status' => 'received',
            'is_received' => 1
        ]);

        $statusUpdate = Order::where('id', $this->getOrderID);

        if ($this->completedOrder === true) {
            $statusUpdate->update([
                             'order_status' => 'received',
                         ]);
        } else {
            $statusUpdate->update([
                'order_status' => 'incomplete',
            ]);
        }

        $this->modalToggle();
        $this->mount();
        $notificationMessage = 'Items has been received.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);
    }

    private function subsctractBranchOrder()
    {
        $orderItems = OrderDetail::whereIn('id', $this->selectedRecord)->get();

        foreach ($orderItems as $orderItem) {
            (int)$itemQty = $orderItem->quantity;
            // 34

            $stockItems = Stock::where('item_id', $orderItem->item_id)->where('quantity', '>', 0)->orderBy('created_at')->get();

            foreach ($stockItems as $stockItem) {
                if ($itemQty > (int)$stockItem->quantity) {
                    Stock::where('id', (int)$stockItem->id)->update([
                         'quantity'=> 0
                     ]);

                    $itemQty -= $stockItem->quantity;
                } else {
                    Stock::where('id', $stockItem->id)->decrement('quantity', $itemQty);

                    break;
                }
            }
        }

        $statusUpdate = Order::where('id', $this->getOrderID);

        if ($this->completedOrder === true) {
            $statusUpdate->update([
                             'order_status' => 'received',
                         ]);
        } else {
            $statusUpdate->update([
                'order_status' => 'incomplete',
            ]);
        }

        $this->modalToggle();
        $this->mount();
    }


    public function deleteArrayItem()
    {
        $id = $this->orders[$this->Index]['id'];
        // $odID = $this->updateID;
        $data =Order::leftJoin('order_details', 'orders.id', '=', 'order_details.order_id')
                    ->where('orders.id', $id);
        OrderDetail::where('order_id', $id)->delete();
        // Order::find($id)->delete();
        $data->delete();


        // $filtered = $this->orders->reject(function ($value, $key) use ($id) {
        //     return $value->id === $id;
        // });

        // $filtered->all();
        // $this->orders = $filtered;
        $this->modalToggle('Delete');
        $notificationMessage2 = 'Record successfully deleted.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'error',
            'messagePrimary'   => $notificationMessage2
        ]);
    }

    public function modalToggle($formAction = null)
    {
        $this->clearForm();
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

    public function updatedSupplierId()
    {
        if ($this->supplier_id === "None") {
            $this->reset([
                'item_id',
                'unitType',
                'quantity',
                'unitPrice',
                'total_amount',
            ]);

            return;
        }

        $user = Auth()->user()->branch_id;
        if ($user != 1) {
            $this->itemList = $this->items->toArray();
        } else {
            $collection = ItemPrice::where('supplier_id', (int) $this->supplier_id)
            ->get();

            $filtered = $collection->filter(function ($value, $key) {
                return $value->count() > 0;
            });

            $this->itemList = $filtered->all();
        }
    }

    public function updatedItemId()
    {
        $this->unitName = Item::where('id', (int) $this->item_id)->get();

        if ($this->unitName->pluck('fixed_unit')->first() === 1) {
            $this->unitString = "Unit";
            $this->unitType = $this->item_id;
            $this->unit_id = $this->item_id;
            $this->unitPriceID = ItemPrice::where('item_id', $this->item_id)->where('supplier_id', $this->supplier_id)->first();
            $this->loadPrice();
        }
    }

    public function updatedUnitId()
    {
        if ($this->unit_id === "None") {
            $this->reset([
                'quantity',
                'unitPrice',
                'total_amount',
            ]);

            return;
        }

        $explodeResult = explode(' ', $this->unit_id);

        $unitId = (int) $explodeResult[0];
        $this->unitString = $explodeResult[1];

        $this->unitPriceID = ItemPrice::where('item_id', (int) $unitId)->where('supplier_id', $this->supplier_id)->first();

        $this->unitPrice = $this->unitPriceID->price_perPieces;

        if ($this->unitString === "Unit") {
            $this->unitPrice = $this->unitPriceID->price_perUnit;
        }

        if ($this->quantity > 0) {
            $this->computeTotalAmount();
        }
    }

    public function loadPrice()
    {
        $unitPrice = ItemPrice::where('item_id', (int) $this->item_id)->where('supplier_id', $this->supplier_id)->first();
        $this->unitPrice = $unitPrice->price_perUnit;
    }

    public function updatedQuantity()
    {
        $this->computeTotalAmount();
    }

    private function computeTotalAmount()
    {
        $this->total_amount = number_format($this->quantity * $this->unitPrice, 2, '.', '');
    }
}
