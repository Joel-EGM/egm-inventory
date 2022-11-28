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


    public $layoutTitle = 'New Item';
    public $itemprices = [];
    public $itemName;
    public $unitName;
    public $piecesPerUnit;
    public $items;
    public $suppliers;


    protected $rules = [
        'itemName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'unitName' => 'bail|required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
        'piecesPerUnit' => 'bail|required|numeric',
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
            'itemName',
            'unitName',
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
            $item = ItemPrice::create([

                'item_name' => $this->itemName,

                'unit_name' => $this->unitName,

                'pieces_perUnit' => $this->piecesPerUnit,

            ]);

            $this->itemprices->push($item);

            $this->clearForm();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->itemprices[$this->Index]['id'];
            ItemPrice::whereId($id)->update([

                'item_name' => $this->itemName,

                'unit_name' => $this->unitName,

                'pieces_perUnit' => $this->piecesPerUnit,

            ]);

            $this->itemprices[$this->Index]['item_name'] = $this->itemName;
            $this->itemprices[$this->Index]['unit_name'] = $this->unitName;
            $this->itemprices[$this->Index]['pieces_perUnit'] = $this->piecesPerUnit;

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
            'itemName',
            'unitName',
            'piecesPerUnit',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'itemName',
            'unitName',
            'piecesPerUnit',
        ]);
    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;
        // dd($this->itemprices[$this->Index]);
        $this->itemName = $this->itemprices[$this->Index]['item_name'];
        $this->unitName = $this->itemprices[$this->Index]['unit_name'];
        $this->piecesPerUnit = $this->itemprices[$this->Index]['pieces_perUnit'];

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
                $this->formTitle = 'New Item';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }
}
