<?php

namespace App\Livewire\User;

use App\Livewire\BaseComponent;

class ManageUser extends BaseComponent
{
    public function render()
    {
        return $this->view('livewire.user.manage-user', []);
    }
}
