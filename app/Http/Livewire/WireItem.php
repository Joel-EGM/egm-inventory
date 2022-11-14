<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;
use Carbon\Carbon;

class WireItem extends Component implements FieldValidationMessage
{
    use ModalVariables;


    public $layoutTitle = 'New Item';
    public $itemId;
    public $items = null;
    public $itemName;
    public $unitName;
    public $piecesPerUnit;


    protected $rules = [
        'itemName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'unitName'     => 'bail|required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
        'piecesPerUnit'         => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->refreshTable();
    }

    public function refreshTable()
    {
        $this->items = Item::all();
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

    public function clearForm()
    {
        $this->reset([
            'itemName',
            'unitName',
            'piecesPerUnit',
        ]);
    }

    public function submit()
    {
        $validatedItem = $this->validate();

        Item::create([

            'item_name' => $this->itemName,

            'unit_name' => $this->unitName,

            'pieces_perUnit' => $this->piecesPerUnit,

        ]);


        $this->clearForm();
        $this->refreshTable();
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
