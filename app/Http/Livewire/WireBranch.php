<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Branch;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;

class WireBranch extends Component
{
    use ModalVariables;


    public $layoutTitle = 'New Branch';
    public $branches = null;
    public $branchName;
    public $branchAddress;
    public $branchContactNo;


    protected $rules = [
        'branchName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'branchAddress' => 'bail|required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
        'branchContactNo' => 'bail|required|numeric',
    ];

    public function mount()
    {
        $this->branches = Branch::all();
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'branchName',
            'branchAddress',
        ];

        if (in_array($propertyName, $wire_models)) {
            $this->$propertyName = ucwords(strtolower($this->$propertyName));
        }


        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.branch');
    }

    public function clearFormVariables()
    {
        $this->reset([
            'isFormOpen',
            'isDeleteOpen',
            'Index',
            'formTitle',
            'branchName',
            'branchAddress',
            'branchContactNo',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'branchName',
            'branchAddress',
            'branchContactNo',
        ]);
    }

    public function submit()
    {
        $validatedItem = $this->validate();


        $branch = Branch::create([

            'branch_name' => $this->branchName,

            'branch_address' => $this->branchAddress,

            'branch_contactNo' => $this->branchContactNo,

        ]);

        $this->branches->push($branch);

        $this->clearForm();

        $notificationMessage = 'Record successfully created.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage
        ]);
    }

    public function modalToggle($formAction = null)
    {
        if (!$formAction) {
            if ($this->Index === null) {
                $this->formTitle = 'New Branch';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }
}
