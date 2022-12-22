<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Supplier;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemPrice;
use App\Models\OrderDetail;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;
use Carbon\Carbon;

class WireOrder extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'Create Order';

    public $order_id;
    public $orders;
    public $order_details;
    public $orderArrays = [];
    public $order_date;
    public $order_status;

    public $supplier_id;
    public $supplier_name;

    public $branches;
    public $branch_id;
    public $branch_name;

    public $items;
    public $item_id;
    public $item_name;
    public $quantity;

    public $price;
    public $total_amount;

    public $unitName = [];
    public $unitPrice;
    public $unit_id;
    public $unitType;

    protected $rules = [
        'item_id' => 'bail|required',
        'order_date'  => 'bail|required|date',
        'supplier_id' => 'bail|required',
        'branch_id' => 'bail|required',
        'unitType' => 'bail|required',
        'quantity'  => 'bail|required|numeric',
        'unitPrice'  => 'bail|required|numeric',
        'total_amount'  => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->items = Item::all();
        $this->suppliers = Supplier::all();
        $this->branches = Branch::all();
        $this->orders = Order::all();
        $this->order_details = OrderDetail::all();
        $this->order_date = Carbon::now()->format('Y-m-d');
    }

    public function updatedQuantity()
    {
        $this->total_amount = $this->quantity * $this->unitPrice;
    }

    public function render()
    {
        return view('livewire.order');
    }

    // public function updated($propertyName)
    // {
    //     $wire_models = [
    //         'branch_id',
    //         'item_id',
    //         'supplier_id',
    //     ];

    //     if (in_array($propertyName, $wire_models)) {
    //         $this->$propertyName = ucwords(strtolower($this->$propertyName));
    //     }


    //     $this->validateOnly($propertyName);
    // }

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

                ]);

                $this->orders->push($orders);

                $this->clearForm();

                $this->modalToggle();

                $notificationMessage = 'Record successfully created.';

                $this->dispatchBrowserEvent('show-message', [
                    'notificationType' => 'success',
                    'messagePrimary'   => $notificationMessage
                ]);
            }
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
        // dd($this->orders[$this->Index]);
        $this->order_date = $this->orders[$this->Index]['order_date'];
        $this->branch_id = $this->orders[$this->Index]['branch_id'];
        $this->supplier_id = $this->orders[$this->Index]['supplier_id'];
        $this->item_id = $this->orders[$this->Index]['item_id'];
        $this->unitType = $this->orders[$this->Index]['unit_name'];
        $this->quantity = $this->orders[$this->Index]['quantity'];
        $this->unitPrice = $this->orders[$this->Index]['price'];
        $this->total_amount = $this->orders[$this->Index]['total_amount'];


        if (!$formAction) {
            $this->formTitle = 'Edit Order';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete Order';
            $this->isDeleteOpen = true;
        }
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
        // explode
        $explodeResult = explode(' ', $this->unit_id);
        // dd($explodeResult);
        // pass data to variable
        $unitId = (int) $explodeResult[0];
        $unitString = $explodeResult[1];

        //pull out data from database
        $unitPrice = ItemPrice::where('item_id', (int) $unitId)->first();

        //create condition to load price unit or per pieces
        $this->unitPrice = $unitPrice->price_perPieces;

        if ($unitString === "Unit") {
            $this->unitPrice = $unitPrice->price_perUnit;
        }

        if ($this->quantity > 0) {
            $this->total_amount = $this->quantity * $this->unitPrice;
        }
    }
}
