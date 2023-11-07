<?php

namespace App\Services;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserManageService
{
     public function getRows($filter): \Illuminate\Database\Eloquent\Builder
     {
        try
        {
            return User::query()
                ->when($filter['name'], fn($q,$name ) => $q->where('name' , 'like' , "%$name%") )
                ->latest();
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
     }

    public function store($form)
    {
        try
        {
            $insert = $form;
            unset($insert['role']);
            $user = User::updateOrCreate(['email' => $insert['email']], $insert);
            $user->roles()->attach($form['role']);
            return $user;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function delete($id)
    {
        try
        {
            return User::where('id', $id)->delete();
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function show($id)
    {
        try
        {
            $res = User::find($id);
            $user = [
                'name' => $res->name,
                'email' => $res->email,
                'role'  => $res?->roles?->first()?->id
            ];
            return $user;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function update($user_data)
    {
        try
        {
            $update = $user_data;
            if(empty($update['password'])) {
                unset($update['password']);
            }else{
                $update['password'] = Hash::make($update['password']);
            }
            unset($update['role']);
            $user = User::find($user_data['id']);
            $user->update($update);
            $user->roles()->sync([$user_data['role']]);
            return $user;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
}
