<?php

namespace App\Livewire;

use App\Models\Site;
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

    public $title = 'Dashboard';
    public function render()
    {
        $data['sites'] = $this->rows;
        return $this->view('livewire.dashboard', $data);
    }

    public function getRowsQueryProperty()
    {
        $query = Site::query()
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
