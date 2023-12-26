<?php

namespace App\Livewire\Admin;

use App\Livewire\BaseComponent;
use App\Services\AdminEmailService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;
use Exception;

class EmailList extends BaseComponent
{
    protected $listeners = ['deleteConfirm' => 'confirmedDelete', 'deleteCancel'=> 'cancelDelete'];

    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    private $service;

    public $email_id = null;

    public $filter = [
        'email' => null
    ];

    public $email = [
        'is_active' => 1,
        'email'     => null,
    ];

    public function boot()
    {
        $this->service = New AdminEmailService();
    }

    public function render()
    {
        $data['emails'] = $this->rows;
        return $this->view('livewire.admin.email-list', $data);
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

    public function openNewAdminEmailModal()
    {
        $this->init();
        $this->resetErrorBag();
        $this->dispatch('openAdminEmailModal');
    }

    public function init()
    {
        $this->email_id = null;
        $this->email = [
            'is_active' => 1,
            'email'     => null,
        ];
    }

    public function submitAdminEmail()
    {
        if($this->email_id){
            return $this->updateAdminEmail();
        }else{
            return $this->addNewAdminEmail();
        }
    }

    public function addNewAdminEmail()
    {
        $rules = [
            'email.email'    => 'required|email:rfc,dns|unique:admin_emails,email',
        ];

        $messages = [
            'email.email.required' => 'The Email field is required.',
            'email.email.email'    => 'The Email address is not valid.',
            'email.email.unique'   => 'The Email already exist.',
        ];

        $this->validate($rules, $messages);
        try {
            $res = $this->service->store($this->email);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'New Admin Email', 'message' => 'New Admin Email added successfully']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'New Admin Email', 'message' => 'Admin Email not added successfully']);
            }
            $this->reset();
            $this->hideModal();
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'New Admin Email', 'message' => $e->getMessage()]);
        }
    }

    public function openEditAdminEmailModal($id)
    {
        $this->resetErrorBag();
        $this->email_id = $id;
        $res = $this->service->show($id);
        $this->email = $res;
        $this->dispatch('openAdminEmailModal');
    }

    public function updateAdminEmail()
    {
        $rules = [
            'email.email'    => 'required|email:rfc,dns|unique:admin_emails,email,'.$this->email_id.',id',
        ];
        $messages = [
            'email.email.required' => 'The Email field is required.',
            'email.email.email'    => 'The Email address is not valid.',
            'email.email.unique'   => 'The Email already exist.',
        ];

        $this->validate($rules, $messages);
        try {
            $this->email['id'] = $this->email_id;
            $res = $this->service->update($this->email);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'Update Admin Email', 'message' => 'Admin Email Update successfully.']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'Update Admin Email', 'message' => 'Admin Email not updated successfully.']);
            }
            $this->reset();
            $this->hideModal();
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Update Admin Email', 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        $this->email_id = $id;
        $this->dispatch('show-delete-notification');
    }

    public function confirmedDelete()
    {
        try {
            $res = $this->service->delete($this->email_id);
            if($res) {
                $this->email_id = null;
                $this->dispatch('deleted', ['message' => 'Admin Email deleted successfully']);
                return redirect()->back();
            }
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Delete Admin Email', 'message' => 'Something went wrong.']);
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Delete Admin Email', 'message' => $e->getMessage()]);
        }
    }

    public function cancelDelete()
    {
        $this->email_id = null;
    }
}
