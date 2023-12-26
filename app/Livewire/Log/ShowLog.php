<?php

namespace App\Livewire\Log;

use App\Livewire\BaseComponent;
use App\Models\Site;
use App\Services\LogService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;

class ShowLog extends BaseComponent
{
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;
    public $site_id;
    public $filter = [ 'dates' => null ];

    private $service;
    public function boot()
    {
        $this->service = New LogService();
    }

    public function mount($site_id)
    {
        $this->site_id = $site_id;
    }

    public function render()
    {
        $data['logs'] = $this->rows;
        $data['info'] = $this->getInfo();
        return $this->view('livewire.log.show-log', $data);
    }

    private function getInfo()
    {
        return Site::query()->with(['contacts:site_id,email'])->find($this->site_id);
    }

    public function getRowsQueryProperty()
    {
        $query = $this->service->getLogs($this->site_id, $this->filter);

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }

    public function download()
    {
        return $this->service->downloadLogs($this->site_id, $this->filter);
    }
}
