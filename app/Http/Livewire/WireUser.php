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

class WireUser extends Component implements FieldValidationMessage
{
    use ModalVariables;
    use WireVariables;
    use TrackDirtyProperties;

    public $layoutTitle = 'New User';

    public function render()
    {
        return view('livewire.user');
    }

    protected $rules = [
        'userName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'userEmail' => 'bail|required|email',
        'userRole' => 'bail|required',
        'branch_id' => 'bail|required',
        'password' => 'bail|required|min:8|confirmed',
        'password_confirmation' => 'bail|required|min:8',
    ];

    public function mount()
    {
        $this->allusers = User::all();
        $this->branches = Branch::all();
    }

    public function updated($propertyName)
    {
        $wire_models = [
            'userName',
            'usereMail',
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

        if (is_null($this->Index)) {
            $user = User::create([

                'name' => $this->userName,

                'email' => $this->userEmail,

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

                'name' => $this->userName,

                'email' => $this->userEmail,

                'branch_id' => $this->branch_id,

                'role' => $this->userRole,

                'password' => Hash::make($this->password),

            ]);


            $this->allusers[$this->Index]['name'] = $this->userName;

            $this->allusers[$this->Index]['email'] = $this->userEmail;

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
            'userName',
            'userEmail',
            'password',
            'password_confirmation',
        ]);
    }

    public function clearForm()
    {
        $this->reset([
            'userName',
            'userEmail',
            'password',
            'password_confirmation',
        ]);
    }

    public function selectArrayItem($Index, $formAction = null)
    {
        $this->Index = $Index;

        $this->userName = $this->allusers[$this->Index]['name'];

        $this->userEmail = $this->allusers[$this->Index]['email'];

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
