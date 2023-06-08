<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Item;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Traits\TrackDirtyProperties;
use Livewire\WithPagination;

use App\Http\Interfaces\FieldValidationMessage;

class WireItem extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;
    use WithPagination;


    public $layoutTitle = 'New Item';
    public $search = '';
    public $updateID;


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
        $page = (int)$this->paginatePage;

        return view('livewire.item', [
            'listItems' =>
            Item::where('item_name', 'like', '%'.$this->search.'%')->paginate($page),
        ]);
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
        } finally {
            $this->updatedDirtyProperties($propertyName, $this->$propertyName);

        }
    }

    public function submit()
    {
        $validatedItem = $this->validate();
        if (is_null($this->Index)) {

            $item = Item::create([


                'item_name' => $this->itemName,

                'unit_name' => $this->unitIName,

                'pieces_perUnit' => $this->piecesPerUnit,

                'reorder_level' => $this->reorder_level,

                'fixed_unit' => !$this->fixedUnit ? 0 : 1,
            ]);


            $this->allitems->push($item);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

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

        if (!$formAction) {
            $this->formTitle = 'Edit Item';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete Item';
            $this->isDeleteOpen = true;
        }
    }


    public function modalDelete($id, $formAction = null)
    {
        $this->deleteID = $this->allitems->where('id', $id)->pluck('id');

        if ($formAction) {
            $this->formTitle = 'Delete Item';
            $this->isDeleteOpen = true;
        }
    }

    public function deleteArrayItem()
    {
        $id = $this->deleteID;
        Item::where('id', $id)->delete();


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

    public function modalEdit($id, $formAction = null)
    {
        $this->updateID = $id;
        $this->itemName = $this->allitems->where('id', $this->updateID)->pluck('item_name')->first();
        $this->unitIName = $this->allitems->where('id', $this->updateID)->pluck('unit_name')->first();
        $this->piecesPerUnit = $this->allitems->where('id', $this->updateID)->pluck('pieces_perUnit')->first();
        $this->reorder_level = $this->allitems->where('id', $this->updateID)->pluck('reorder_level')->first();
        $this->fixedUnit = $this->allitems->where('id', $this->updateID)->pluck('fixed_unit')->first();


        if (isset($this->allitems[$this->updateID]['dirty_fields'])) {
            $this->dirtyProperties = $this->allitems[$this->updateID]['dirty_fields'];
        }

        if ($formAction) {
            $this->formTitle = 'Edit Item';
            $this->isFormOpen = true;
        }
    }

    public function itemUpdate()
    {
        if($this->isDirty) {
            Item::where('id', $this->updateID)->update([


                'item_name' => $this->itemName,

                'unit_name' => $this->unitIName,

                'pieces_perUnit' => $this->piecesPerUnit,

                'reorder_level' => $this->reorder_level,

                'fixed_unit' => $this->fixedUnit,

            ]);


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
