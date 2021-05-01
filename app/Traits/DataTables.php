<?php

namespace App\Traits;

use Illuminate\Support\Str;
use App\Exports\DatatableExport;
use Maatwebsite\Excel\Facades\Excel;

trait DataTables
{
    public $paginate = 10;
    public $search = "";
    public $defaultPageOptions = [10, 20, 30];
    public $sortColumn = 'id';
    public $sortDirection = 'asc';

    public $selected = [];
    public $selectPage = false;
    public $selectAll = false;

    protected $query;

    public function getQuery()
    {
        $this->buildDBQuery();

        return $this->query;
    }

    public function builder()
    {
        return $this->model::query();
    }

    public function buildDBQuery()
    {
        $this->query = $this->builder();
        $this
            ->loadRelation() //load relation if defined
            ->searchRecord() //perform search
            ->sortRecord(); //sort records
    }

    public function loadRelation()
    {
        if (!empty($this->relation)) {
            $this->query->with($this->relation);
        }
        return $this;
    }

    public function searchRecord()
    {
        if (!$this->search) {
            return $this;
        }

        if (!empty($this->search)) {
            $term = "%$this->search%";
            $this->query->where(function ($query) use ($term) {
                foreach ($this->searchColumns as $key => $column) {
                    $contains = Str::contains($column, '.');
                    if ($contains) {
                        $collection = Str::of($column)->explode('.');
                        $relation = $collection[0];
                        $field = $collection[1];
                        if ($key == 0) {
                            $query->where($column, 'like', $term);
                            $query->whereHas($relation, function ($query) use ($term, $field) {
                                $query->where($field, 'like', $term);
                            });
                        } else {
                            $query->orWhereHas($relation, function ($query) use ($term, $field) {
                                $query->where($field, 'like', $term);
                            });
                        }
                    } else {
                        if ($key == 0) {
                            $query->where($column, 'like', $term);
                        } else {
                            $query->orWhere($column, 'like', $term);
                        }
                    }
                }
            });
        }
        return $this;
    }

    public function sortByColumn($column)
    {
        if ($this->sortColumn == $column) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            $this->reset('sortDirection');
            $this->sortColumn = $column;
        }
    }

    public function sortRecord()
    {
        $this->query->orderBy($this->sortColumn, $this->sortDirection);

        return $this;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPaginate()
    {
        $this->resetPage();
    }

    public function export()
    {
        return Excel::download(new DatatableExport($this->getQuery()->get()), 'DatatableExport.xlsx');
    }

    public function exportSelected()
    {
        $query = $this->getQuery()
            ->whereKey($this->selected)
            ->get();

        $heading = array_column($this->columns, 'value');
        return Excel::download(new DatatableExport($query, $heading), 'DatatableExport.xlsx');
    }

    public function deleteSelected()
    {
        $this->model::whereKey($this->selected)->delete();
        $this->selected = [];
        $this->selectAll = false;
        $this->selectPage = false;
        $this->dispatchBrowserEvent('showToast', [
            'message' => 'Selected Records were deleted Successfully',
            'type' => 'success'
        ]);
    }

    public function deleteRecord($record_id)
    {
        $record = $this->model::findOrFail($record_id);
        $record->delete();
        $this->dispatchBrowserEvent('showToast', [
            'message' => 'Record deleted Successfully',
            'type' => 'success'
        ]);
    }

    public function setPaginationOption($options)
    {
        $this->defaultPageOptions = $options;
    }
}
