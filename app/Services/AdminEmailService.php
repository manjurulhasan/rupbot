<?php

namespace App\Services;

use App\Models\AdminEmail;
use Exception;
use Illuminate\Support\Facades\Cache;

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
            $admin = AdminEmail::updateOrCreate(['email' => $form['email']], $form);
            Cache::forget('admin_emails');
            return $admin;
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
            $delete = AdminEmail::where('id', $id)->delete();
            Cache::forget('admin_emails');
            return $delete;
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
            Cache::forget('admin_emails');
            return $email;
        }
        catch(Exception $ex)
        {
            throw $ex;
        }
    }
}
