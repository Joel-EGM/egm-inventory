<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Traits\TrackDirtyProperties;


use App\Http\Interfaces\FieldValidationMessage;

class WireItem extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;

    public $layoutTitle = 'New Item';


    protected $rules = [
        'itemName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'unitIName' => 'bail|required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
        'piecesPerUnit' => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->allitems = Item::all();
    }

    public function render()
    {
        return view('livewire.item');
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'itemName',
            'unitIName',
        ];

        if (in_array($propertyName, $wire_models)) {
            $this->$propertyName = ucwords(strtolower($this->$propertyName));
        }


        try {
            $this->validateOnly($propertyName);
        } catch (\Throwable $th) {
            //throw $th;
        } finally {
            $this->updatedDirtyProperties($propertyName, $this->$propertyName);

        }
    }

    public function submit()
    {
        $validatedItem = $this->validate();
        logger($this->Index);
        if (is_null($this->Index)) {

            logger("true part");
            if ($this->fixedUnit === false) {
                $item = Item::create([


                    'item_name' => $this->itemName,

                    'unit_name' => $this->unitIName,

                    'pieces_perUnit' => $this->piecesPerUnit,

                    'reorder_level' => $this->reorder_level,

                    'fixed_unit' => 0,
                ]);
            } else {
                $item = Item::create([


                    'item_name' => $this->itemName,

                    'unit_name' => $this->unitIName,

                    'pieces_perUnit' => $this->piecesPerUnit,

                    'reorder_level' => $this->reorder_level,

                    'fixed_unit' => 1,
                ]);
            }

            $this->allitems->push($item);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            if($this->isDirty) {
                logger("false part");

                $id = $this->allitems[$this->Index]['id'];
                Item::whereId($id)->update([


                    'item_name' => $this->itemName,

                    'unit_name' => $this->unitIName,

                    'pieces_perUnit' => $this->piecesPerUnit,

                    'reorder_level' => $this->reorder_level,

                    'fixed_unit' => $this->fixedUnit,

                ]);

                $this->allitems[$this->Index]['item_name'] = $this->itemName;
                $this->allitems[$this->Index]['unit_name'] = $this->unitIName;
                $this->allitems[$this->Index]['pieces_perUnit'] = $this->piecesPerUnit;
                $this->allitems[$this->Index]['reorder_level'] = $this->reorder_level;
                $this->allitems[$this->Index]['fixed_unit'] = $this->fixedUnit;

                $this->allitems->push();
                $this->Index = null;
                $this->clearForm();

                $notificationMessage = 'Record successfully updated.';
            } else {

                $notificationMessage = 'No changes were detected';
            }

            $this->modalToggle();

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
            'unitIName',
            'piecesPerUnit',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'itemName',
            'unitIName',
            'piecesPerUnit',
        ]);
    }

    public function selectArrayItem($index, $formAction = null)
    {
        $this->Index = $index;
        $this->itemName = $this->allitems[$this->Index]['item_name'];
        $this->unitIName = $this->allitems[$this->Index]['unit_name'];
        $this->piecesPerUnit = $this->allitems[$this->Index]['pieces_perUnit'];
        $this->reorder_level = $this->allitems[$this->Index]['reorder_level'];
        $this->fixedUnit = $this->allitems[$this->Index]['fixed_unit'];



        if (isset($this->allitems[$this->Index]['dirty_fields'])) {
            $this->dirtyProperties = $this->allitems[$this->Index]['dirty_fields'];
        }

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
        $id = $this->allitems[$this->Index]['id'];
        Item::find($id)->delete();


        $filtered = $this->allitems->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->allitems = $filtered;
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
