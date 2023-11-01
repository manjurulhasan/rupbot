<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseComponent;

class EmailList extends BaseComponent
{
    public function render()
    {
        return $this->view('livewire.admin.email-list' , []);
    }
}
