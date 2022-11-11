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
    public $items = null;
    public $itemName;
    public $unitName;
    public $piecesPerUnit;


    public function mount()
    {
        if (!$this->items) {
            $this->items = Item::all();
        }
    }

    public function render()
    {
        return view('livewire.item');
    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'isDeleteOpen',
            'itemIndex',
            'formTitle',
            'itemName',
            'unitName',
            'piecesPerUnit',
        ]);
    }

    public function modalToggle($formAction = null)
    {
        if (!$formAction) {
            if ($this->itemIndex === null) {
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
