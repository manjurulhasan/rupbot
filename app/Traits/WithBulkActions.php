<?php

namespace App\Traits;

trait WithBulkActions
{
    public $selectPage = false;
    public $selectAll = false;
    public $selected = [];

    public function renderingWithBulkActions()
    {
//        if ($this->selectAll) $this->selectPageRows();
        if ($this->selectAll) {
            $this->applyLimit(1000);
            $this->selectPageRows();
            $this->applyLimit(10);
        }
    }

    public function updatedSelected()
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function updatedSelectPage($value)
    {
        if ($value) return $this->selectPageRows();

        $this->selectAll = false;
        $this->selected = [];
    }

    public function selectPageRows()
    {
        $this->selected = $this->rows->pluck('id')->map(fn($id) => (string) $id);
    }

    public function selectAll()
    {
        $this->selectAll = true;
    }

    public function getSelectedRowsQueryProperty()
    {
        return (clone $this->rowsQuery)
            ->unless($this->selectAll, fn($query) => $query->whereKey($this->selected));
    }

    public function applyLimit($limit)
    {
        $this->perPage = $limit;
    }
}
