<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ItemPrice;
use App\Models\Item;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;

class WireItemPrice extends Component
{
    use ModalVariables;


    public $layoutTitle = 'Add Item Price';
    public $itemprices = [];
    public $supplier_id;
    public $suppliers_name;
    public $item_id;
    public $item_name;
    public $price;
    public $items;
    public $suppliers;
    public $priceArrays = [];


    protected $rules = [
        'supplier_id' => 'required',
        'item_id' => 'required',
        'price' => 'required',
    ];

    public function mount()
    {
        $this->items = Item::all();
        $this->suppliers = Supplier::all();
        $this->itemprices = ItemPrice::all();
    }

    public function render()
    {
        return view('livewire.item-price');
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'supplier_id',
            'item_id',
        ];

        if (in_array($propertyName, $wire_models)) {
            $this->$propertyName = ucwords(strtolower($this->$propertyName));
        }


        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        $validatedItem = $this->validate();

        if (is_null($this->Index)) {
            foreach ($this->priceArrays as $priceArray) {
                $price = ItemPrice::create([

                    'supplier_id' => $priceArray['supplier_id'],

                    'item_id' => $priceArray['item_id'],

                    'price' => $priceArray['price'],

                ]);
            }
            $this->itemprices->push($price);

            $this->clearForm();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->itemprices[$this->Index]['id'];
            ItemPrice::whereId($id)->update([

                'supplier_id' => $this->supplier_id,

                'item_id' => $this->item_id,

                'price' => $this->price,

            ]);

            $this->itemprices[$this->Index]['supplier_id'] = $this->supplier_id;
            $this->itemprices[$this->Index]['item_id'] = $this->item_id;
            $this->itemprices[$this->Index]['price'] = $this->price;

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

    public function addPriceArray()
    {
        $this->validate();

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

        array_push($this->priceArrays, [
            'supplier_id' => $this->supplier_id,
            'suppliers_name' => $supplierName,
            'item_id' => $this->item_id,
            'item_name' => $itemName,
            'price' => $this->price,
        ]);
    }

    public function removeItem()
    {
    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'isDeleteOpen',
            'Index',
            'formTitle',
            'supplier_id',
            'item_id',
            'price',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'supplier_id',
            'item_id',
            'price',
        ]);
    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;
        // dd($this->itemprices[$this->Index]);
        $this->supplier_id = $this->itemprices[$this->Index]['supplier_id'];
        $this->item_id = $this->itemprices[$this->Index]['item_id'];
        $this->price = $this->itemprices[$this->Index]['price'];

        if (!$formAction) {
            $this->formTitle = 'Edit Item';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete Item';
            $this->isDeleteOpen = true;
        }
    }

    public function deleteArrayItem()
    {
        $id = $this->itemprices[$this->Index]['id'];

        ItemPrice::find($id)->delete();


        $filtered = $this->itemprices->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->itemprices = $filtered;
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
                $this->formTitle = 'Add Item Price';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }
}
