<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ItemPrice;
use App\Models\Item;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Interfaces\FieldValidationMessage;
use App\Http\Traits\TrackDirtyProperties;
use Livewire\WithPagination;

class WireItemPrice extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;
    use WithPagination;


    public $layoutTitle = 'Add Item Price';
    public $search = '';
    public $filteredSuppliers;
    public $filteredSupp;
    public $listSuppliers;
    public $editID;
    public $updateID;


    protected $rules = [
        'supplier_id' => 'bail|required',
        'item_id' => 'bail|required',
        'price_perUnit' => 'bail|required|numeric',
        'price_perPieces' => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->allitems = Item::select('id', 'item_name')->get();
        $this->allsuppliers = Supplier::select('id', 'suppliers_name')->get();
        $this->itemprices = ItemPrice::with('items', 'suppliers')->get();

        $sup_id = $this->itemprices->pluck('supplier_id');

        $this->filteredSupp = $this->allsuppliers->whereIn('id', $sup_id);

        $this->listSuppliers = $this->allsuppliers->pluck('id');

        $this->filteredSuppliers = $this->itemprices
            ->whereIn('supplier_id', $this->listSuppliers)
            ->unique('supplier_id');

        $this->filteredSuppliers->values()->all();

    }

    public function render()
    {
        $page = (int)$this->paginatePage;
        $filtered = $this->itemprices->filter(function ($value) {
            return $value->supplier_id === (int)$this->sortList;
        });
        $filteredSupplierList = $filtered->all();

        if($this->sortList === 'all') {
            return view('livewire.item-price', [     'listItemPrices' =>
            ItemPrice::with('items', 'suppliers')->whereHas('items', function ($query) {
                $query->where('item_name', 'like', '%'.$this->search.'%');
            })->paginate($page),]);
        } else {
            return view('livewire.item-price', [
                'listItemPrices' =>
                collect($filteredSupplierList)->paginateArray($page),
            ]);
        }
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
        }
    }

    public function addPriceArray()
    {
        $this->validate();

        $supplierName = '';
        foreach ($this->allsuppliers as $supplier) {
            if ($supplier->id === (int) $this->supplier_id) {
                $supplierName = $supplier->suppliers_name;
                break;
            }
        }

        $itemName = '';
        foreach ($this->allitems as $item) {
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

        if (isset($this->itemprices[$this->Index]['dirty_fields'])) {
            $this->dirtyProperties = $this->itemprices[$this->Index]['dirty_fields'];
        }

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
        $this->deleteID = $this->itemprices
            ->where('id', $id)
            ->pluck('id');

        if ($formAction) {
            $this->formTitle = 'Delete Item Price';
            $this->isDeleteOpen = true;
        }
    }

    public function deleteArrayItem()
    {
        $id = $this->deleteID;

        ItemPrice::where('id', $id)->delete();

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
        $this->unitAName = Item::select()->where('id', $this->item_id)->get();
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


    public function modalEdit($id, $formAction = null)
    {
        $this->updateID = $id;

        $this->supplier_id = $this->itemprices
            ->where('id', $this->updateID)
            ->pluck('supplier_id')
            ->first();

        $this->item_id = $this->itemprices
            ->where('id', $this->updateID)
            ->pluck('item_id')
            ->first();

        $this->price_perUnit = $this->itemprices
            ->where('id', $this->updateID)
            ->pluck('price_perUnit')
            ->first();

        $this->price_perPieces = $this->itemprices
            ->where('id', $this->updateID)
            ->pluck('price_perPieces')
            ->first();

        if ($formAction) {
            $this->formTitle = 'Edit Price';
            $this->isFormOpen = true;
        }
    }

    public function itemUpdate()
    {
        if($this->isDirty) {
            $id = $this->updateID;
            $price = ItemPrice::where('id', $id)->update([
                'supplier_id' => $this->supplier_id,

                'item_id' => $this->item_id,

                'price_perUnit' => $this->price_perUnit,

                'price_perPieces' => $this->price_perPieces,
            ]);

            $this->itemprices->push();
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
