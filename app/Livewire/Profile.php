<?php

namespace App\Livewire;

use App\Services\UserManageService;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Exception;

class Profile extends BaseComponent
{
    public $user = [
        'name'     => null,
        'email'    => null,
        'password' => null
    ];
    public function mount()
    {
        $this->user['name']  = auth()->user()->name;
        $this->user['email'] = auth()->user()->email;
    }

    public function render()
    {
        return $this->view('livewire.profile', []);
    }

    public function updateProfile()
    {
        $rules = [
            'user.name'     => 'required|min:3|max:50',
            'user.email'    => 'required|email:rfc,dns|unique:users,email,'.auth()->user()->id.',id'
        ];
        $messages = [
            'user.name.required'     => 'The Name field is required.',
            'user.password.required' => 'The Password field is required.',
            'user.password.min'      => 'This Password is too short',
            'user.password.max'      => 'This Password is too long',
            'user.email.unique'      => 'The Email address already exist.',
            'user.email.required'    => 'The Email field is required.',
            'user.email.email'       => 'The Email address is not valid.'
        ];
        $this->validate($rules, $messages);
        try{
            $res = (new UserManageService())->updateProfile($this->user);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'Profile Update', 'message' => 'Profile successfully updated']);
            }else{
                $this->dispatch('notify', ['type' => 'error', 'title' => 'Profile Update', 'message' => 'Profile not updated']);
            }
            return true;
        } catch (Exception $e){
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Profile Update', 'message' => $e->getMessage()]);
        }
    }
}
