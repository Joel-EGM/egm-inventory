<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;

class WireItem extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'New Item';

    public $items = [];
    public $itemName;
    public $unitName;

    public $piecesPerUnit;


    protected $rules = [
        'itemName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'unitName' => 'bail|required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
        'piecesPerUnit' => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->items = Item::all();
    }

    public function render()
    {
        return view('livewire.item');
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
            $item = Item::create([

                'item_name' => $this->itemName,

                'unit_name' => $this->unitName,

                'pieces_perUnit' => $this->piecesPerUnit,

            ]);

            $this->items->push($item);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->items[$this->Index]['id'];
            Item::whereId($id)->update([

                'item_name' => $this->itemName,

                'unit_name' => $this->unitName,

                'pieces_perUnit' => $this->piecesPerUnit,

            ]);

            $this->items[$this->Index]['item_name'] = $this->itemName;
            $this->items[$this->Index]['unit_name'] = $this->unitName;
            $this->items[$this->Index]['pieces_perUnit'] = $this->piecesPerUnit;

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
        // dd($this->items[$this->Index]);
        $this->itemName = $this->items[$this->Index]['item_name'];
        $this->unitName = $this->items[$this->Index]['unit_name'];
        $this->piecesPerUnit = $this->items[$this->Index]['pieces_perUnit'];

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
        $id = $this->items[$this->Index]['id'];
        Item::find($id)->delete();


        $filtered = $this->items->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->items = $filtered;
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
