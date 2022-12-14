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

class WireOrder extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'Create Order';
    public $order_id;
    public $orders;
    public $orderArrays = [];
    public $supplier_id;
    public $supplier_name = [];
    public $order_date;
    public $order_status;
    public $branch_id;
    public $branch_name = [];
    public $item_id;
    public $item_name = [];
    public $quantity;
    public $price;
    public $total_amount;
    public $items;
    public $branches;
    public $unitName = [];
    public $unitPrice;
    public $unit_id;



    protected $rules = [
        'item_id' => 'required',
        'order_date'  => 'required|date',
        'supplier_id' => 'required',
        'branch_id' => 'required',
        'quantity'  => 'required|numeric',
        'price'  => 'required|numeric',
        'total_amount'  => 'required|numeric',
    ];

    public function mount()
    {
        $this->items = Item::all();
        $this->suppliers = Supplier::all();
        $this->branches = Branch::all();
        $this->orders = Order::all();
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
            $item = Order::create([

                'branch_name' => $this->branch_id,
                'supplier_name' => $this->supplier_id,
                'item_name' => $this->item_id,

            ]);

            $this->orders->push($item);

            $this->clearForm();

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

        array_push($this->orderArrays, [
        'branch_id'  => $this->branch_id,
        'branch_name'  => $branchName,
        'item_id' => $this->item_id,
        'item_name'  => $itemName,
        'unit_name' => $this->unitName,
        'order_date' => $this->order_date,
        'supplier_id' => $this->supplier_id,
        'suppliers_name' => $supplierName,
        'quantity' => $this->quantity,
        'price' => $this->price,
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
            'branch_id',
            'item_id',
            'supplier_id',
        ]);
    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;
        // dd($this->orders[$this->Index]);
        $this->branch_id = $this->orders[$this->Index]['branch_id'];
        $this->order_date = $this->orders[$this->Index]['order_date'];
        $this->supplier_id = $this->orders[$this->Index]['supplier_id'];
        $this->item_id = $this->orders[$this->Index]['item_id'];
        $this->quantity = $this->orders[$this->Index]['quantity'];
        $this->price = $this->orders[$this->Index]['price'];
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
        $unitPrice = ItemPrice::select('price_perUnit')->where('item_id', (int) $this->unit_id)->first();
        $this->unitPrice = $unitPrice->price_perUnit;
    }
}
