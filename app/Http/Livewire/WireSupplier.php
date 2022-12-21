<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;

class WireSupplier extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'New Supplier';

    public $suppliers =[];
    public $supplierName;
    public $supplierEmail;
    public $supplierContactNo;


    protected $rules = [
        'supplierName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'supplierEmail' => 'bail|required|email',
        'supplierContactNo' => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->suppliers = Supplier::all();
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'supplierName',
        ];

        if (in_array($propertyName, $wire_models)) {
            $this->$propertyName = ucwords(strtolower($this->$propertyName));
        }


        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.supplier');
    }

    public function submit()
    {
        $validatedItem = $this->validate();

        if (is_null($this->Index)) {
            $supplier = Supplier::create([

                'suppliers_name' => $this->supplierName,

                'suppliers_email' => $this->supplierEmail,

                'suppliers_contact' => $this->supplierContactNo,

            ]);

            $this->suppliers->push($supplier);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->suppliers[$this->Index]['id'];
            Supplier::whereId($id)->update([

                'suppliers_name' => $this->supplierName,

                'suppliers_email' => $this->supplierEmail,

                'suppliers_contact' => $this->supplierContactNo,

            ]);


            $this->suppliers[$this->Index]['suppliers_name'] = $this->supplierName;
            $this->suppliers[$this->Index]['suppliers_email'] = $this->supplierEmail;
            $this->suppliers[$this->Index]['suppliers_contact'] = $this->supplierContactNo;

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
            'supplierName',
            'supplierEmail',
            'supplierContactNo',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'supplierName',
            'supplierEmail',
            'supplierContactNo',
        ]);
    }

    public function selectArrayItem($Index, $formAction = null)
    {
        $this->Index = $Index;

        $this->supplierName = $this->suppliers[$this->Index]['suppliers_name'];
        $this->supplierEmail = $this->suppliers[$this->Index]['suppliers_email'];
        $this->supplierContactNo = $this->suppliers[$this->Index]['suppliers_contact'];

        if (!$formAction) {
            $this->formTitle = 'Edit Supplier';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete Supplier';
            $this->isDeleteOpen = true;
        }
    }

    public function deleteArrayItem()
    {
        $id = $this->suppliers[$this->Index]['id'];
        Supplier::find($id)->delete();


        $filtered = $this->suppliers->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->suppliers = $filtered;
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
                $this->formTitle = 'New Supplier';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }
}
