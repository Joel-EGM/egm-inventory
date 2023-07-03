<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class ModalForm extends Component
{
    public $formTitle;
    

    public function __construct($formTitle)
    {
        $this->formTitle = $formTitle;
    }

    public function render()
    {
        return view('components.modal.modal-form');
    }
}
