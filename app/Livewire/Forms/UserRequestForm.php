<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Rule;
use Livewire\Form;

class UserRequestForm extends Form
{
    #[Rule('required|in:Super-Admin,Admin,Viewer')]
    public $role = null;

    #[Rule('required')]
    public $name = null;

    #[Rule('required|email:rfc,dns|unique:users,email')]
    public $email = null;

    #[Rule('required')]
    public $password = null;

}
