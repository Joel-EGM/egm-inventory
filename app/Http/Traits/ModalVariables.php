<?php

namespace App\Http\Traits;

trait ModalVariables
{
    public $isFormOpen   = false;
    public $isDeleteOpen = false;
    public $itemIndex    = null;
    public $formTitle    = null;
    public $clientId     = null;
    public $idToDelete   = [];

    public function updatedIsFormOpen()
    {
        $this->clearAndResetForm();
    }

    public function updatedIsDeleteOpen()
    {
        $this->clearAndResetDelete();
    }

    public function clearAndResetForm()
    {
        if (!$this->isFormOpen) {
            $this->clearFormVariables();
            $this->resetValidation();
        }
    }

    public function clearAndResetDelete()
    {
        if (!$this->isDeleteOpen) {
            $this->clearFormVariables();
        }
    }
}
