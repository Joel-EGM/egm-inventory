<?php

namespace App\View\Components\Modals;

use Illuminate\View\Component;

class ModalNotification extends Component
{

    public function __construct()
    {
       
    }

 
    public function render()
    {
        return view('components.modal.modal-notification');
    }
}
