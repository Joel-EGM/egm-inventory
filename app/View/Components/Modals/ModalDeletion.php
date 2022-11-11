<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class ModalDeletion extends Component
{
    public $formTitle;

    public function __construct($formTitle)
    {
        $this->formTitle = $formTitle;
    }

    public function render()
    {
        return view('components.modal.modal-deletion');
    }
}
