<?php

namespace App\Services;

use App\Models\AdminEmail;
use Exception;

class AdminEmailService
{
    public function getRows($filter): \Illuminate\Database\Eloquent\Builder
    {
        try
        {
            return AdminEmail::query()
                ->when($filter['email'], fn($q,$email ) => $q->where('email' , 'like' , "%$email%") )
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
            return  AdminEmail::updateOrCreate(['email' => $form['email']], $form);
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
            return AdminEmail::where('id', $id)->delete();
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
            $res = AdminEmail::find($id);
            return [
                'email'     => $res?->email,
                'is_active' => $res?->is_active
            ];
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }

    public function update($update_data)
    {
        try
        {

            $email = AdminEmail::find($update_data['id']);
            $email->update($update_data);
            return $email;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
}
