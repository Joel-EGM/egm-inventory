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

    public $layoutTitle = 'New Item';
    public $order_id;
    public $orders;
    public $orderArray = [];
    public $supplier_id;
    public $order_date;
    public $order_status;
    public $branch_id;
    public $item_id;
    public $quantity;
    public $price;
    public $total_amount;
    public $items;
    public $branches;


    protected $rules = [
        'item_id' => 'required',
        'supplier_id' => 'required',
        'branch_id' => 'required',
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
        $this->branch_id = $this->orders[$this->Index]['branch_name'];
        $this->item_id = $this->orders[$this->Index]['item_name'];
        $this->supplier_id = $this->orders[$this->Index]['supplier_name'];

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
}
