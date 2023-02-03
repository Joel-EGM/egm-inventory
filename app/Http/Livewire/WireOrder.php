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
use App\Http\Interfaces\FieldValidationMessage;
use Carbon\Carbon;
use DB;

class WireOrder extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'Create Order';

    public $orderArrays = [];
    public $itemList = [];
    public $unitName = [];
    public $selectedRecord = [];

    public $order_id;
    public $unit_id;

    public $getBranchID;
    public $getOrderID;

    public $orders;
    public $items;
    public $branches;

    public $branch_name;
    public $order_details;
    public $order_date;
    public $order_status;
    public $details;

    public $quantity;
    public $price;
    public $total_amount;
    public $unitPrice;
    public $unitType;

    public $users;

    public $completedOrder = false;


    protected $rules = [
        'item_id' => 'bail|required',
        'order_date'  => 'bail|required|date',
        'supplier_id' => 'bail|required',
        'branch_id' => 'bail|required',
        'unitType' => 'bail|required',
        'quantity'  => 'bail|required|numeric| max: 999',
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

        // $this->users = User::where('id')->get();

        $user = Auth()->user()->branch_id;

        if ($user != 1) {
            $this->orders = Order::where('branch_id', $user)->get();
        } else {
            $this->orders = Order::all();
        }
    }

    public function render()
    {
        return view('livewire.order');
    }

    public function submit()
    {
        $validatedItem = $this->validate();

        if (is_null($this->Index)) {
            $orders = Order::create([

                'branch_id' => $this->branch_id,

                'order_date' => $this->order_date,

                'order_status' => 'pending'

            ]);

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
        } else {
            $id = $this->orders[$this->Index]['id'];
            Order::whereId($id)->update([

                'branch_name' => $this->branch_id,
                'supplier_name' => $this->supplier_id,
                'item_name' => $this->item_id,

            ]);

            $this->orders[$this->Index]['branch_name'] = $this->branch_id;

            $this->orders[$this->Index]['supplier_name'] = $this->supplier_id;

            $this->orders[$this->Index]['item_name'] = $this->item_id;


            $this->Index = null;
            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully updated.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        }
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
    }

    public function removeItem($index)
    {
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
            'order_date',
            'branch_id',
            'item_id',
            'supplier_id',
            'unitType',
            'unit_id',
            'quantity',
            'unitPrice',
            'total_amount',
        ]);
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

        if (!$formAction) {
            $this->formTitle = 'Edit Order';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete Order';
            $this->isDeleteOpen = true;
        }
    }

    public function viewOrderDetails($id, $formAction = null)
    {
        $this->details = OrderDetail::where('order_id', $id)->get();
        $this->getOrderID = Order::where('id', $id)->pluck('id');
        $this->getBranchID = Order::where('id', $id)->pluck('branch_id')->first();

        if (!$formAction) {
            $this->formTitle = 'Order Details';
            $this->isFormOpen = true;
        }
    }

    public function saveMethod()
    {
        // $getBranchName = Branch::where('id', $this->getBranchID)->get();
        if ($this->getBranchID != 1) {
            $this->subsctractBranchOrder();
        } else {
            $this->saveCheckedItems();
        }
    }

    private function saveCheckedItems()
    {
        $getid = OrderDetail::whereIn('id', $this->selectedRecord)->get()->toArray();

        foreach ($getid as $detail) {
            Stock::create([

                'order_id' => $detail['order_id'],

                'item_id' => $detail['item_id'],

                'quantity' => $detail['quantity'],

                'price' => $detail['price'],

            ]);
            break;
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
        //find selected Data in OrderDetail
        $orderItems = OrderDetail::whereIn('id', $this->selectedRecord)->get();

        //find the corresponding item_id of OrderDetail in Stocks table
        foreach ($orderItems as $orderItem) {
            (int)$itemQty = $orderItem->quantity;
            // 34

            $stockItems = Stock::where('item_id', $orderItem->item_id)->where('quantity', '>', 0)->orderBy('created_at')->get();

            foreach ($stockItems as $stockItem) {
                //set conditon to deduct order quantity(OQ) to stock quantity(SQ) if OQ is greater than SQ if not then deduct the remaining quantity to the next SQ

                if ($itemQty > (int)$stockItem->quantity) {
                    // qty 12
                    //update db, set quantity to 0
                    Stock::where('id', (int)$stockItem->id)->update([
                         'quantity'=> 0
                     ]);

                    //deduct order itemQty to stockItem if stockItem not sufficient then check
                    $itemQty -= $stockItem->quantity;
                } else {
                    //update db decrement quantity by #itemQty
                    Stock::where('id', $stockItem->id)->decrement('quantity', $itemQty);
                    // exit loop

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
        Order::find($id)->delete();

        $filtered = $this->orders->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });

        $filtered->all();
        $this->orders = $filtered;
        $this->modalToggle('Delete');
        $notificationMessage2 = 'Record successfully deleted.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'error',
            'messagePrimary'   => $notificationMessage2
        ]);
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

        $collection = ItemPrice::where('supplier_id', (int) $this->supplier_id)
        ->get();

        $filtered = $collection->filter(function ($value, $key) {
            return $value->count() > 0;
        });

        $this->itemList = $filtered->all();
    }

    public function updatedItemId()
    {
        $this->unitName = Item::where('id', (int) $this->item_id)->get();
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
        $unitString = $explodeResult[1];

        $unitPrice = ItemPrice::where('item_id', (int) $unitId)->where('supplier_id', $this->supplier_id)->first();

        $this->unitPrice = $unitPrice->price_perPieces;



        if ($unitString === "Unit") {
            $this->unitPrice = $unitPrice->price_perUnit;
        }

        if ($this->quantity > 0) {
            $this->computeTotalAmount();
        }
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
