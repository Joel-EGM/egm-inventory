<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Interfaces\FieldValidationMessage;

class WireSupplier extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;

    public $layoutTitle = 'New Supplier';

    protected $rules = [
        'getsupplierName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'getsupplierEmail' => 'bail|required|email',
        'getsupplierContactNo' => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->getsuppliers = Supplier::all();
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'getsupplierName',
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

                'suppliers_name' => $this->getsupplierName,

                'suppliers_email' => $this->getsupplierEmail,

                'suppliers_contact' => $this->getsupplierContactNo,

            ]);

            $this->getsuppliers->push($supplier);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->getsuppliers[$this->Index]['id'];
            Supplier::whereId($id)->update([

                'suppliers_name' => $this->getsupplierName,

                'suppliers_email' => $this->getsupplierEmail,

                'suppliers_contact' => $this->getsupplierContactNo,

            ]);

            $this->getsuppliers[$this->Index]['suppliers_name'] = $this->getsupplierName;

            $this->getsuppliers[$this->Index]['suppliers_email'] = $this->getsupplierEmail;

            $this->getsuppliers[$this->Index]['suppliers_contact'] = $this->getsupplierContactNo;

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
            'getsupplierName',
            'getsupplierEmail',
            'getsupplierContactNo',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'getsupplierName',
            'getsupplierEmail',
            'getsupplierContactNo',
        ]);
    }

    public function selectArrayItem($Index, $formAction = null)
    {
        $this->Index = $Index;

        $this->getsupplierName = $this->getsuppliers[$this->Index]['suppliers_name'];

        $this->getsupplierEmail = $this->getsuppliers[$this->Index]['suppliers_email'];

        $this->getsupplierContactNo = $this->getsuppliers[$this->Index]['suppliers_contact'];

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
        $id = $this->getsuppliers[$this->Index]['id'];
        Supplier::find($id)->delete();


        $filtered = $this->getsuppliers->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->getsuppliers = $filtered;
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
