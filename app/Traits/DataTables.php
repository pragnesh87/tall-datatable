<?php

namespace App\Traits;

use Illuminate\Support\Str;

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

    public function getQuery($relation = [])
    {
        $this->buildDBQuery($relation);

        return $this->query;
    }

    public function builder()
    {
        return $this->model::query();
    }

    public function buildDBQuery($relation)
    {
        $this->query = $this->builder();
        $this->searchRecord($relation) //perform search
            ->sortRecord();
    }

    public function searchRecord($relation = [])
    {
        if (!empty($relation)) {
            $this->query->with($relation);
        }
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
}
