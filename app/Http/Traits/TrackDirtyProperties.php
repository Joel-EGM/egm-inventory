<?php

namespace App\Http\Traits;

trait TrackDirtyProperties
{
    public array $dirtyProperties = [];

    public bool $isDirty = false;

    public function updating($propertyName)
    {
        $this->updatingDirtyProperties($propertyName, $this->$propertyName);
    }

    public function updated($propertyName)
    {
        $this->updatedDirtyProperties($propertyName, $this->$propertyName);
    }

    public function checkIfArrayObjectIsDirty($arrayObject, $idToDelete)
    {
        if (count($idToDelete) > 0) {
            $this->isDirty = true;

            return;
        }

        $this->isDirty = false;

        $dirtyProperties = $arrayObject;

        foreach ($dirtyProperties as $key => $value) {
            if ($value['id'] === 0) {
                $this->isDirty = true;

                return;
            }

            if ($value['id'] > 0 && array_key_exists('dirty_fields', $value) && count($value['dirty_fields']) > 0) {
                $this->isDirty = true;

                return;
            }
        }
    }

    private function updatingDirtyProperties($propertyName, $propertyValue)
    {
        if (count($this->dirtyProperties) === 0) {
            $this->addDirtyProperty($propertyName, $propertyValue);
        } else {
            $isFieldExist = false;

            foreach ($this->dirtyProperties as $key => $value) {
                if (array_key_exists($propertyName, $value)) {
                    $isFieldExist = true;
                }
            }

            if (! $isFieldExist) {
                $this->addDirtyProperty($propertyName, $propertyValue);
            }
        }
    }

    private function updatedDirtyProperties($propertyName, $propertyValue)
    {
        $dirtyProperties = $this->dirtyProperties;

        foreach ($dirtyProperties as $key => $value) {
            if (array_key_exists($propertyName, $value) && ($value[$propertyName] == $propertyValue || is_null($value[$propertyName]))) {
                unset($this->dirtyProperties[$key]);
            }
        }

        if (count($this->dirtyProperties) === 0) {
            $this->isDirty = false;
        }
    }

    private function addDirtyProperty($propertyName, $propertyValue)
    {
        array_push($this->dirtyProperties, [
            $propertyName => $propertyValue,
        ]);

        $this->isDirty = true;
    }
}
