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
use Livewire\WithPagination;

class WireBranch extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;
    use WithPagination;

    public $layoutTitle = 'New Branch';
    public $branchID;
    public $branch_name;
    public $branch_address;
    public $search = '';
    public $updateID;
    public $deleteID;
    public $acc_number;
    public $area_number;
    public $has_inventory = false;
    public $can_createall = false;

    protected function rules()
    {
        return [
        'branch_name' => [
                'bail',
                'required',
                'regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i',
                'min:2','max:50',

            Rule::unique('branches')
                ->where(function ($query) {
                    return $query
                    ->where('branch_name', $this->branch_name)
                    ->where('branch_address', $this->branch_address);

                })->ignore($this->branchID)],

        'branch_address' => [
                'bail',
                'required',
                'regex:/^[\pL\s\-\,\.]+$/u',
                'min:2',
                'max:25',

            Rule::unique('branches')
                ->where(function ($query) {
                    return $query
                    ->where('branch_address', $this->branch_address)
                    ->where('branch_name', $this->branch_name);
                })->ignore($this->branchID)],

        'branchContactNo' => 'bail|required|numeric',
        ];
    }

    public function mount()
    {
        $this->allbranches = Branch::select('id', 'branch_name', 'branch_address', 'branch_contactNo', 'status', 'has_inventory', 'can_createall')->get();

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

        } finally {
            $this->updatedDirtyProperties($propertyName, $this->$propertyName);

        }
    }

    public function render()
    {
        $page = (int)$this->paginatePage;
        $filtered = $this->allbranches->filter(function ($value) {
            return $value->status === (int)$this->sortList;
        });
        $filteredBranches = $filtered->all();

        if($this->sortList === 'all') {
            return view('livewire.branch', [
                'activebranches' => Branch::where('branch_name', 'like', $this->search . '%')
                ->paginate($page),]);
        } else {
            return view('livewire.branch', [
                'activebranches' => collect($filteredBranches)
                ->paginateArray($page)]);
        }
    }

    public function submit()
    {
        try {
            $this->validate();
        } catch (ValidationException $exception) {

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
        if (is_null($this->Index)) {
            $branch = Branch::updateOrCreate([

                'branch_name' => $this->branch_name,

                'branch_address' => $this->branch_address,

                'branch_contactNo' => $this->branchContactNo,

                'acc_number' => $this->acc_number,

                'area_number' => $this->area_number,

                'has_inventory' => $this->has_inventory === false ? 0 : 1,

                'can_createall' => $this->can_createall === false ? 0 : 1,

            ]);

            $this->allbranches->push($branch);

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
            'branch_name',
            'branch_address',
            'branchContactNo',
            'acc_number',
            'area_number',
            'has_inventory',
            'can_createall',
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

        $this->acc_number = $this->allbranches[$this->Index]['acc_number'];

        $this->area_number = $this->allbranches[$this->Index]['area_number'];

        $this->has_inventory = $this->allbranches[$this->Index]['has_inventory'];

        $this->can_createall = $this->allbranches[$this->Index]['can_createall'];




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

    public function changeStatus(Branch $branch, $status)
    {
        $branch->update(['status' => $status]);

        $notificationMessage2 = 'Status has been updated successfully.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage2
        ]);
    }

    public function modalEdit($id, $formAction = null)
    {
        $this->updateID = $id;
        $this->branch_name = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('branch_name')
            ->first();

        $this->branch_address = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('branch_address')
            ->first();

        $this->branchContactNo = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('branch_contactNo')
            ->first();

        $this->acc_number = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('acc_number')
            ->first();

        $this->area_number = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('area_number')
            ->first();

        $this->has_inventory = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('has_inventory')
            ->first();

        $this->can_createall = $this->allbranches
            ->where('id', $this->updateID)
            ->pluck('can_createall')
            ->first();
        if (isset($this->allbranches[$this->updateID]['dirty_fields'])) {
            $this->dirtyProperties = $this->allbranches[$this->updateID]['dirty_fields'];
        }

        if ($formAction) {
            $this->formTitle = 'Edit Branch';
            $this->isFormOpen = true;
        }
    }

    public function itemUpdate()
    {
        if($this->isDirty) {
            Branch::where('id', $this->updateID)->update([

                'branch_name' => $this->branch_name,

                'branch_address' => $this->branch_address,

                'branch_contactNo' => $this->branchContactNo,

                'acc_number' => $this->acc_number,

                'area_number' => $this->area_number,

                'has_inventory' => $this->has_inventory === false ? 0 : 1,

                'can_createall' => $this->can_createall === false ? 0 : 1,

            ]);


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

    public function modalDelete($id, $formAction = null)
    {
        $this->deleteID = $this->allbranches
            ->where('id', $id)
            ->pluck('id');

        if ($formAction) {
            $this->formTitle = 'Delete Item';
            $this->isDeleteOpen = true;
        }
    }


    public function deleteArrayItem()
    {
        $id = $this->deleteID;
        Branch::where('id', $id)->delete();


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
}
