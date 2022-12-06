<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Http\Traits\ModalVariables;
use App\Http\Interfaces\FieldValidationMessage;
use Illuminate\Support\Facades\Hash;

class WireUser extends Component implements FieldValidationMessage
{
    use ModalVariables;

    public $layoutTitle = 'New User';
    public $users =[];
    public $userName;
    public $userEmail;
    public $password;
    public $password_confirmation;


    public function render()
    {
        return view('livewire.user');
    }

    protected $rules = [
        'userName' => 'bail|required|regex:/^[A-Za-z0-9 .\,\-\#\(\)\[\]\Ñ\ñ]+$/i|min:2|max:50',
        'userEmail' => 'bail|required|email',
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required|min:8',
    ];

    public function mount()
    {
        $this->users = User::all();
    }

    // public function updated($propertyName)
    // {
    //     if (in_array($propertyName, ['password', 'password_confirmation'])) {
    //         return;
    //     }


    //     $wire_models = [
    //         'userName',
    //     ];

    //     if (in_array($propertyName, $wire_models)) {
    //         $this->$propertyName = ucwords(strtolower($this->$propertyName));
    //     }


    //     $this->validateOnly($propertyName);
    // }

    public function submit()
    {
        $validatedItem = $this->validate();

        if (is_null($this->Index)) {
            $user = User::create([

                'name' => $this->userName,

                'email' => $this->userEmail,

                'password' => Hash::make($this->password),

            ]);

            $this->users->push($user);

            $this->clearForm();

            $notificationMessage = 'Record successfully created.';

            $this->dispatchBrowserEvent('show-message', [
                'notificationType' => 'success',
                'messagePrimary'   => $notificationMessage
            ]);
        } else {
            $id = $this->users[$this->Index]['id'];
            User::whereId($id)->update([

                'name' => $this->userName,

                'email' => $this->userEmail,

                'password' => Hash::make($this->password),

            ]);


            $this->users[$this->Index]['name'] = $this->userName;
            $this->users[$this->Index]['email'] = $this->userEmail;
            $this->users[$this->Index]['password'] = $this->password;

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

        $this->userName = $this->users[$this->Index]['name'];
        $this->userEmail = $this->users[$this->Index]['email'];
        $this->password = $this->users[$this->Index]['password'];

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
        $id = $this->users[$this->Index]['id'];
        User::find($id)->delete();


        $filtered = $this->users->reject(function ($value, $key) use ($id) {
            return $value->id === $id;
        });


        $filtered->all();
        $this->users = $filtered;
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
}
