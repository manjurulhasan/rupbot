<?php

namespace App\Livewire;

use App\Services\SiteManagerService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;
use Exception;

class Dashboard extends BaseComponent
{
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    public $email_count = 1;
    public $disabled    = false;

    public $filter = [
        'site_name' => ''
    ];

    public $site = [
        'project' => null,
        'url'     => null,
        'manager' => null
    ];

    public $emails = [[
        'id' => 0,
        'email'   => null,
    ]];
    private $service;
    public function boot()
    {
        $this->service = New SiteManagerService();
    }

    public function render()
    {
        $data['info'] = $this->service->lastCheck();
        $data['sites'] = $this->rows;
        return $this->view('livewire.dashboard', $data);
    }

    public function getRowsQueryProperty()
    {
        $query = $this->service->getDashboardRows($this->filter);

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
            'site.project' => 'required',
            'site.manager' => 'required',
            'site.url'     => 'required|url|unique:sites,url',
            'emails.*.email' => 'required|email:rfc,dns'
        ];
        $messages = [
            'site.project.required'  => 'The Project field is required.',
            'site.manager.required'  => 'The Manager field is required.',
            'site.url.required'      => 'The URL field is required.',
            'site.url.unique'        => 'The URL already exist.',
            'emails.*.email.required' => 'The Email field is required.',
            'emails.*.email.email'   => 'The Email address is not valid.'
        ];
        $this->validate($rules, $messages);
        try {
            $res = $this->service->addSite($this->site, $this->emails);
            if($res){
                $this->dispatch('notify', ['type' => 'success', 'title' => 'New Site', 'message' => 'New site successfully added']);
            }else{
                $this->dispatch('notify', ['type' => 'info', 'title' => 'New Site', 'message' => 'Site not added successfully']);
            }
            $this->hideModal();
            $this->reset('site');
            $this->reset('emails');
            $this->init();
        } catch (Exception $e){
            $this->dispatch('notify', ['type' => 'error', 'title' => 'New Site', 'message' => $e->getMessage()]);
        }
    }

    public function openNewSiteModal()
    {
        $this->reset('site');
        $this->reset('emails');
        $this->reset();
        $this->resetErrorBag();
        $this->dispatch('openNewSiteModal');
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
