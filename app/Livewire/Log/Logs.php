<?php

namespace App\Livewire\Log;

use App\Livewire\BaseComponent;
use App\Services\LogService;
use App\Traits\WithBulkActions;
use App\Traits\WithCachedRows;
use App\Traits\WithPerPagePagination;
use App\Traits\WithSorting;

class Logs extends BaseComponent
{
    use WithPerPagePagination;
    use WithCachedRows;
    use WithSorting;
    use WithBulkActions;

    public $filter = [
        'url' => ''
    ];
    private $service;
    public function boot()
    {
        $this->service = New LogService();
    }

    public function render()
    {
        $data['logs'] = $this->rows;
        return $this->view('livewire.log.logs', $data);
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
}
