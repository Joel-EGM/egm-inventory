<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Branch;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Interfaces\FieldValidationMessage;
use App\Http\Traits\TrackDirtyProperties;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class WireBranch extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;

    public $layoutTitle = 'New Branch';
    public $branchID;
    public $branch_name;
    public $branch_address;


    // protected $rules = [
    //     'branch_name' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
    //     'branch_address' => 'bail|required|regex:/^[\pL\s\-\,\.]+$/u|min:2|max:25',
    //     'branchContactNo' => 'bail|required|numeric',
    // ];

    protected function rules()
    {
        return [
            'branch_name' => ['bail','required','regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i','min:2','max:50',
            Rule::unique('branches')

            ->where(function ($query) {
                return $query
                ->where('branch_name', $this->branch_name)
                ->where('branch_address', $this->branch_address);

            })->ignore($this->branchID)],



            'branch_address' => ['bail','required','regex:/^[\pL\s\-\,\.]+$/u','min:2','max:25',

            Rule::unique('branches')
            ->where(function ($query) {
                return $query

                ->where('branch_address', $this->branch_address)
                ->where('branch_name', $this->branch_name);

            })->ignore($this->branchID)],




        'branchContactNo' => 'bail|required|numeric',
            // 'password' => 'bail|required|min:8|confirmed',
            // 'password_confirmation' => 'bail|required|min:8',

        ];
    }

    public function mount()
    {
        $this->allbranches = Branch::all();

        // dd($this->allbranches->orderdet);
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'branch_name',
            'branch_address',
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

    public function render()
    {
        return view('livewire.branch');
    }

    public function submit()
    {
        // $validatedItem = $this->validate();
        try {

            $this->validate();
            // $this->validate();


        } catch (ValidationException $exception) {
            // dd('gg');

            $messages = $exception->validator->errors();
            if($messages->first('branch_name') === 'The branch name has already been taken.') {
                $this->dispatchBrowserEvent('show-message', [
                    'notificationType' => 'error',
                    'messagePrimary'   => $messages
                ]);
                return;
            };
            throw $exception;

        }
    // logger($validate);
        if (is_null($this->Index)) {
            $branch = Branch::updateOrCreate([

                'branch_name' => $this->branch_name,

                'branch_address' => $this->branch_address,

                'branch_contactNo' => $this->branchContactNo,

            ]);

            $this->allbranches->push($branch);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {

            if($this->isDirty) {
                $id = $this->allbranches[$this->Index]['id'];
                Branch::whereId($id)->update([

                    'branch_name' => $this->branch_name,

                    'branch_address' => $this->branch_address,

                    'branch_contactNo' => $this->branchContactNo,

                ]);


                $this->allbranches[$this->Index]['branch_name'] = $this->branch_name;

                $this->allbranches[$this->Index]['branch_address'] = $this->branch_address;

                $this->allbranches[$this->Index]['branch_contactNo'] = $this->branchContactNo;

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
            'branch_name',
            'branch_address',
            'branchContactNo',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'branch_name',
            'branch_address',
            'branchContactNo',
        ]);
    }

    public function selectArrayItem($Index, $formAction = null)
    {
        $this->Index = $Index;

        $this->branchID = $this->allbranches[$this->Index]['id'];

        $this->branch_name = $this->allbranches[$this->Index]['branch_name'];

        $this->branch_address = $this->allbranches[$this->Index]['branch_address'];

        $this->branchContactNo = $this->allbranches[$this->Index]['branch_contactNo'];


        if (isset($this->allbranches[$this->Index]['dirty_fields'])) {
            $this->dirtyProperties = $this->allbranches[$this->Index]['dirty_fields'];
        }


        if (!$formAction) {
            $this->formTitle = 'Edit Branch';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete Branch';
            $this->isDeleteOpen = true;
        }
    }

    public function deleteArrayItem()
    {
        $id = $this->allbranches[$this->Index]['id'];
        Branch::find($id)->delete();


        $filtered = $this->allbranches->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->allbranches = $filtered;
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
