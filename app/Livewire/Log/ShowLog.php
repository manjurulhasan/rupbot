<?php

namespace App\Livewire\Log;

use App\Livewire\BaseComponent;
use App\Models\Log;
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
        $query = Log::query()
            ->when($this->filter['dates'], function($q) {
                $date   = explode(' to ',$this->filter['dates']);
                $start  = date('Y-m-d', strtotime($date[0])) . ' 00:00:00';
                $end    = date('Y-m-d', strtotime(end($date))) . ' 23:59:59';
                return $q->whereBetween('created_at',[$start, $end]);
            })
            ->where('site_id', $this->site_id)
            ->latest();

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
        return (new LogService())->downloadLogs();
    }
}
