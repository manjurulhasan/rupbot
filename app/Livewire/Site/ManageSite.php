<?php

namespace App\Livewire\Site;

use App\Livewire\BaseComponent;
use App\Models\Site;
use App\Services\SiteManagerService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;
use Exception;

class ManageSite extends BaseComponent
{
    protected $listeners = ['deleteConfirm' => 'confirmedDelete', 'deleteCancel'=> 'cancelDelete'];

    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    public $site_id = null;
    public $disabled = false;
    public $email_count = 1;
    public $remove_site = null;

    public $filter = [
        'site_name' => ''
    ];

    public $site = [
        'project' => null,
        'url'     => null,
        'manager' => null
    ];

    public $emails = [[
        'id'    => 0,
        'email' => null,
    ]];
    private $service;
    public function boot()
    {
        $this->service = New SiteManagerService();
    }
    public function render()
    {
        $data['sites'] = $this->rows;
        return $this->view('livewire.site.manage-site', $data);
    }

    public function getRowsQueryProperty()
    {
        $query = Site::query()
            ->with(['contacts:site_id,email'])
            ->when($this->filter['site_name'], fn($q,$site_name ) => $q->where('project' , 'like' , "%$site_name%") )
            ->latest();

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function init()
    {
        $this->site = [
        'project' => null,
        'url'     => null,
        'manager' => null
        ];

        $this->emails = [[
        'id'    => 0,
        'email' => null,
        ]];
        $this->email_count = 1;
        $this->disabled = false;
    }

    public function addNewSite()
    {
        $rules = [
            'site.project'   => 'required',
            'site.manager'   => 'required',
            'site.url'       => 'required|url|unique:sites,url',
            'emails.*.email' => 'required|email:rfc,dns'
        ];
        $messages = [
            'site.project.required' => 'The Project field is required.',
            'site.manager.required' => 'The Manager field is required.',
            'site.url.required'     => 'The URL field is required.',
            'site.url.unique'       => 'The URL already exist.',
            'emails.*.email.required' => 'The Email field is required.',
            'emails.*.email.email'  => 'The Email address is not valid.'
        ];
        $this->validate($rules, $messages);
        try {
            $res = (new SiteManagerService())->addSite($this->site, $this->emails);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'New Site', 'message' => 'New site successfully added']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'New Site', 'message' => 'Site not added successfully']);
            }
            $this->reset();
            $this->hideModal();
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'New Site', 'message' => $e->getMessage()]);
        }
    }

    public function openNewSiteModal()
    {
        $this->init();
        $this->reset('site');
        $this->reset('emails');
        $this->reset();
        $this->resetErrorBag();
        $this->dispatch('openNewSiteModal');
    }

    public function openEditSiteModal($site_id)
    {
        $this->init();
        $this->site_id = $site_id;
        $res = $this->service->showSite($site_id);
        $this->site = $res['site'];
        if(count($res['emails']) > 0) {
            $this->emails = $res['emails'];
        }
        $this->dispatch('openEditSiteModal');
    }

    public function updateSite()
    {
        $rules = [
            'site.project'   => 'required',
            'site.manager'   => 'required',
            'site.url'       => 'required|url|unique:sites,url,'.$this->site_id.',id',
            'emails.*.email' => 'required|email:rfc,dns'
        ];
        $messages = [
            'site.project.required' => 'The Project field is required.',
            'site.manager.required' => 'The Manager field is required.',
            'site.url.required'     => 'The URL field is required.',
            'site.url.unique'       => 'The URL already exist.',
            'emails.*.email.required' => 'The Email field is required.',
            'emails.*.email.email'  => 'The Email address is not valid.'
        ];
        $this->validate($rules, $messages);
        try {
            $res = $this->service->updateSite($this->site, $this->emails);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'Update Site', 'message' => 'Site Update successfully added']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'Update Site', 'message' => 'Site not Update successfully']);
            }
            $this->hideModal();
            $this->reset('site');
            $this->reset('emails');
        } catch(Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Update Site', 'message' => $e->getMessage()]);
        }
    }

    public function deleteSite($site_id)
    {
        $this->remove_site = $site_id;
        $this->dispatch('show-delete-notification');
    }

    public function confirmedDelete()
    {
        try {
            $res = $this->service->deleteSite($this->remove_site);
            if($res) {
                $this->remove_site = null;
                $this->dispatch('deleted', ['message' => 'Category deleted successfully']);
                return redirect()->back();
            }
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Delete Site', 'message' => 'Something went wrong.']);
        } catch (Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'title' => 'Delete Site', 'message' => $e->getMessage()]);
        }
    }

    public function cancelDelete()
    {
        $this->remove_site = null;
    }

    public function addEmail()
    {
        if($this->email_count < 3){
            $this->emails [] = [
                'id' => 0,
                'email'   => null,
            ];
            $this->email_count += 1;
            $this->disabled = true;
        }
    }
}
