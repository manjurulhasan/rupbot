<?php

namespace App\Livewire\User;

use App\Livewire\BaseComponent;
use App\Livewire\Forms\UserRequestForm;
use App\Services\UserManageService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;
use Exception;

class ManageUser extends BaseComponent
{
    protected $listeners = ['deleteConfirm' => 'confirmedDelete', 'deleteCancel'=> 'cancelDelete'];

//    public UserRequestForm $form;

    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    public $user_id = null;

    public $filter = [
        'name' => ''
    ];

    public $user = [
        'name'     => null,
        'email'    => null,
        'password' => null,
        'role'     => null
    ];

    public function boot()
    {
        $this->service = New UserManageService();
    }

    public function render()
    {
        $data['users'] = $this->rows;
        return $this->view('livewire.user.manage-user', $data);
    }

    public function getRowsQueryProperty()
    {
        $query = $this->service->getRows($this->filter);

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function openNewUserModal()
    {
        $this->init();
        $this->dispatch('openUserModal');
    }

    public function init()
    {
        $this->user_id = null;
        $this->user = [
            'name'     => null,
            'email'    => null,
            'password' => null,
            'role'     => 2
        ];
    }

    public function submitUser()
    {
        if($this->user_id){
            return $this->updateUser();
        }else{
            return $this->addNewUser();
        }
    }

    public function addNewUser()
    {
        $rules = [
            'user.name'     => 'required',
            'user.email'    => 'required|email:rfc,dns|unique:users,email',
            'user.password' => 'required',
            'user.role'     => 'required'
        ];

        $this->validate($rules);
        try {
            $res = $this->service->store($this->user);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'New User', 'message' => 'New User added successfully']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'New User', 'message' => 'User not added successfully']);
            }
            $this->reset();
            $this->hideModal();
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'New User', 'message' => $e->getMessage()]);
        }
    }

    public function openEditUserModal($id)
    {
        $this->user_id = $id;
        $res = $this->service->show($id);
        $this->user = $res;
        $this->dispatch('openUserModal');
    }

    public function updateUser()
    {
        $rules = [
            'user.name'     => 'required',
            'user.email'    => 'required|email:rfc,dns|unique:users,email,'.$this->user_id.',id',
            'user.role'     => 'required'
        ];

        $this->validate($rules);
        try {
            $this->user['id'] = $this->user_id;
            $res = $this->service->update($this->user);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'Update User', 'message' => 'User Update successfully.']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'Update User', 'message' => 'User not updated successfully.']);
            }
            $this->reset();
            $this->hideModal();
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Update User', 'message' => $e->getMessage()]);
        }

//        dd($this->user);
    }

    public function delete($id)
    {
        $this->user_id = $id;
        $this->dispatch('show-delete-notification');
    }

    public function confirmedDelete()
    {
        try {
            $res = $this->service->delete($this->user_id);
            if($res) {
                $this->user_id = null;
                $this->dispatch('deleted', ['message' => 'User deleted successfully']);
                return redirect()->back();
            }
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Delete User', 'message' => 'Something went wrong.']);
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Delete User', 'message' => $e->getMessage()]);
        }
    }

    public function cancelDelete()
    {
        $this->user_id = null;
    }

}
