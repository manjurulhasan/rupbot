<?php

namespace App\Livewire;

use App\Models\Site;
use App\Services\SiteManagerService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;

class Dashboard extends BaseComponent
{
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    public $filter = [
        'site_name' => ''
    ];

    public $site = [
        'project' => null,
        'url'     => null,
        'manager' => null,
        'email'   => null
    ];
    public function render()
    {
        $data['sites'] = $this->rows;
        return $this->view('livewire.dashboard', $data);
    }

    public function getRowsQueryProperty()
    {
        $query = Site::query()
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

    public function addNewSite()
    {
        $res = (new SiteManagerService())->addSite($this->site);
        $this->reset('site');
    }

    public function openNewSiteModal()
    {
        $this->reset('site');
        $this->reset();
        $this->resetErrorBag();
        $this->dispatch('openNewSiteModal');
    }
}
