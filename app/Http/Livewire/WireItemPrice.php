<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ItemPrice;
use App\Models\Item;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;

class WireItemPrice extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'Add Item Price';

    public $items;
    public $itemprices = [];
    public $priceArrays = [];
    public $unitName = [];
    public $suppliers;

    protected $rules = [
        'supplier_id' => 'bail|required',
        'item_id' => 'bail|required',
        'price_perUnit' => 'bail|required|numeric',
        'price_perPieces' => 'bail|required|numeric',
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
        // dd($this->priceArrays);
        if (is_null($this->Index)) {
            foreach ($this->priceArrays as $priceArray) {
                $price = ItemPrice::create([

                    'supplier_id' => $priceArray['supplier_id'],

                    'item_id' => $priceArray['item_id'],

                    'price_perUnit' => $priceArray['price_perUnit'],

                    'price_perPieces' => $priceArray['price_perPieces'],

                ]);

                $this->itemprices->push($price);
                $this->itemprices->all();
            }


            $this->clearForm();
            $this->modalToggle();

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

                'price_perUnit' => $this->price_perUnit,

                'price_perPieces' => $this->price_perPieces,

            ]);

            $this->itemprices[$this->Index]['supplier_id'] = $this->supplier_id;
            $this->itemprices[$this->Index]['item_id'] = $this->item_id;
            $this->itemprices[$this->Index]['price_perUnit'] = $this->price_perUnit;
            $this->itemprices[$this->Index]['price_perPieces'] = $this->price_perPieces;

            $this->itemprices->push();
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
            'price_perUnit' => $this->price_perUnit,
            'price_perPieces' => $this->price_perPieces,

        ]);
    }

    public function removeItem($index)
    {
        unset($this->priceArrays[$index]);
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
            'price_perUnit',
            'price_perPieces',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'supplier_id',
            'item_id',
            'price_perUnit',
            'price_perPieces',
        ]);

        $this->priceArrays = array();
    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;

        $this->supplier_id = $this->itemprices[$this->Index]['supplier_id'];
        $this->item_id = $this->itemprices[$this->Index]['item_id'];
        $this->price_perUnit = $this->itemprices[$this->Index]['price_perUnit'];
        $this->price_perPieces = $this->itemprices[$this->Index]['price_perPieces'];

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

    public function updatedItemId()
    {
        $this->unitName = Item::select()->where('id', $this->item_id)->get();
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
