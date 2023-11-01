<?php

namespace App\Livewire\Auth;

use App\Livewire\PublicBaseComponent;
use Illuminate\Support\Facades\Auth;
use Exception;

class Login extends PublicBaseComponent
{
    public $email    = null;
    public $password = null;

    public function mount()
    {
        if (auth::check()) {
            return redirect('/dashboard');
        }
    }

    public function render()
    {

        return $this->view('livewire.auth.login' , []);
    }

    public function doLogin()
    {
        $this->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ],[
            'password.required' => 'The Password Field is required.',
            'email.required'    => 'The Email Field is required.',
            'email.email'       => 'Please enter valid Email address.'
        ]);
        try{
            if(!Auth::attempt(['email' => $this->email, 'password' => $this->password,'is_active' => 1])){
                throw new Exception('Access denied. Please enter valid credential.');
            }
            return redirect()->intended('dashboard');
        }catch ( Exception $ex){
            session()->flash('error', $ex->getMessage());
        }
    }
}
