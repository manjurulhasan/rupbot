<?php

namespace App\Livewire\Log;

use App\Livewire\BaseComponent;
use App\Models\Log;
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

    public function render()
    {
        $data['logs'] = $this->rows;
        return $this->view('livewire.log.logs', $data);
    }

    public function getRowsQueryProperty()
    {
        $query = Log::query()
            ->when($this->filter['url'], fn($q,$url ) => $q->where('url' , 'like' , "%$url%") )
            ->latest();

        return $this->applySorting($query);
    }

    public function getRowsProperty()
    {
        return $this->cache(function () {
            return $this->applyPagination($this->rowsQuery);
        });
    }
}
