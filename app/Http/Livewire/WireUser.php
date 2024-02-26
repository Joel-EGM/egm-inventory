<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Branch;
use App\Http\Traits\ModalVariables;
use App\Http\Traits\WireVariables;
use App\Http\Interfaces\FieldValidationMessage;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\TrackDirtyProperties;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\WithPagination;

class WireUser extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;
    use WithPagination;

    public $layoutTitle = 'New User';
    public $userID;
    public $name;
    public $email;
    public $ID;


    public function mount()
    {
        $this->allusers = User::select('id', 'name', 'email', 'role', 'branch_id')->get();
        $this->branches = Branch::select('id', 'branch_name')->get();
    }

    public function render()
    {
        $page = (int) $this->paginatePage;

        return view('livewire.user', [
            'listUsers' =>
            User::where('name', 'like', '%' . $this->search . '%')->paginate($page),
        ]);
    }

    protected function rules()
    {
        return [
        'name' => ['bail','required','regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i','min:2','max:50',
                Rule::unique('users')

                ->where(function ($query) {
                    return $query
                    ->where('name', $this->name);

                })->ignore($this->userID)],

        'email' => ['bail','required','email',

                Rule::unique('users')
                ->where(function ($query) {
                    return $query

                    ->where('email', $this->email);
                    ;

                })->ignore($this->userID)],

        'userRole' => 'bail|required',
        'branch_id' => 'bail|required',
        ];
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'name',
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
        try {

            $validate = $this->validate();

        } catch (ValidationException $exception) {
            throw $exception;

        }

        if (is_null($this->Index)) {
            $user = User::create([

                'name' => $this->name,

                'email' => $this->email,

                'branch_id' => $this->branch_id,

                'role' => $this->userRole,

                'password' => Hash::make($this->password),

            ]);

            $this->allusers->push($user);

            $this->clearForm();
            $this->modalToggle();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->allusers[$this->Index]['id'];
            User::whereId($id)->update([

                'name' => $this->name,

                'email' => $this->email,

                'branch_id' => $this->branch_id,

                'role' => $this->userRole,

                'password' => Hash::make($this->password),

            ]);

            $this->allusers[$this->Index]['name'] = $this->name;

            $this->allusers[$this->Index]['email'] = $this->email;

            $this->allusers[$this->Index]['role'] = $this->userRole;

            $this->allusers[$this->Index]['branch_id'] = $this->branch_id;

            $this->allusers[$this->Index]['password'] = $this->password;

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
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'name',
            'email',
            'password',
            'password_confirmation',
        ]);
    }

    public function selectArrayItem($Index, $formAction = null)
    {

        $this->Index = $Index;

        $this->userID = $this->allusers[$this->Index]['id'];

        $this->name = $this->allusers[$this->Index]['name'];

        $this->email = $this->allusers[$this->Index]['email'];

        $this->userRole = $this->allusers[$this->Index]['role'];

        $this->branch_id = $this->allusers[$this->Index]['branch_id'];

        if (isset($this->allusers[$this->Index]['dirty_fields'])) {
            $this->dirtyProperties = $this->allusers[$this->Index]['dirty_fields'];
        }

        if (!$formAction) {
            $this->formTitle = 'Edit User';
            $this->isFormOpen = true;
        } else {
            $this->formTitle = 'Delete User';
            $this->isDeleteOpen = true;
        }
    }

    public function modalEdit($id, $formAction = null)
    {
        $this->ID = $id;

        $this->name = $this->allusers
            ->where('id', $this->ID)
            ->pluck('name')
            ->first();

        $this->email = $this->allusers
            ->where('id', $this->ID)
            ->pluck('email')
            ->first();

        $this->userRole = $this->allusers
            ->where('id', $this->ID)
            ->pluck('role')
            ->first();

        $this->branch_id = $this->allusers
            ->where('id', $this->ID)
            ->pluck('branch_id')
            ->first();

        if (isset($this->allusers[$this->ID]['dirty_fields'])) {
            $this->dirtyProperties = $this->allusers[$this->ID]['dirty_fields'];
        }

        if ($formAction) {
            $this->formTitle = 'Edit Branch';
            $this->isFormOpen = true;
        }
    }

    public function itemUpdate()
    {
        if($this->isDirty) {
            User::where('id', $this->ID)->update([

                'name' => $this->name,

                'email' => $this->email,

                'branch_id' => $this->branch_id,

                'role' => $this->userRole,

                'password' => Hash::make($this->password),
            ]);


            $this->allusers->push();
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

    public function deleteArrayItem()
    {
        $id = $this->allusers[$this->Index]['id'];
        User::find($id)->delete();


        $filtered = $this->allusers->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->allusers = $filtered;
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
                $this->formTitle = 'New User';
            }

            $this->isFormOpen = !$this->isFormOpen;
            $this->clearAndResetForm();
        } else {
            $this->isDeleteOpen = !$this->isDeleteOpen;
            $this->clearAndResetDelete();
        }
    }

    public function changeRole(User $user, $role)
    {
        $user->update(['role' => $role]);

        $notificationMessage2 = 'Role has been updated successfully.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage2
        ]);
    }

    public function changeBranch(User $user, $branch_id)
    {
        $user->update(['branch_id' => $branch_id]);

        $notificationMessage2 = 'Branch has been updated successfully.';

        $this->dispatchBrowserEvent('show-message', [
            'notificationType' => 'success',
            'messagePrimary'   => $notificationMessage2
        ]);
    }
}
