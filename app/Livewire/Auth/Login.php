<?php

namespace App\Livewire\Auth;

use App\Livewire\PublicBaseComponent;

class Login extends PublicBaseComponent
{
    public $email    = null;
    public $password = null;
    public function render()
    {
        return $this->view('livewire.auth.login' , []);
    }

    public function doLogin()
    {
        dd($this->email, $this->password);
    }
}
