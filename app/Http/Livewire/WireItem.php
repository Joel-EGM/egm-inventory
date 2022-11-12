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

    protected $rules = [
        'itemName' => 'required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'unitName'     => 'required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
        'piecesPerUnit'         => 'required|numeric',
    ];

    public $layoutTitle = 'New Item';
    public $items = null;
    public $itemName;
    public $unitName;
    public $piecesPerUnit;


    public function mount()
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

        if (is_null($this->itemIndex)) {
            $validatedItem['id']         = 0;
            $validatedItem['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $validatedItem['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $this->itemsArray[$this->itemIndex]['item_name'] = $this->itemName;
            $this->itemsArray[$this->itemIndex]['unit_name'] = $this->unitName;
            $this->itemsArray[$this->itemIndex]['pieces_perUnit'] = $this->piecesPerUnit;
            $this->itemIndex = null;
        }

        $this->clearForm();
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
