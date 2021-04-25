<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait DataTables
{
    public $paginate = 10;
    public $search = "";
    public $checked = [];
    public $selectPage = false;
    public $selectAll = false;
    public $model;
    public $defaultPageOptions = [10, 20, 30];
    public $sortColumn = 'id';
    public $sortDirection = 'asc';


    public function searchRecord($with = [])
    {
        $records = $this->model::query();
        if (!empty($with)) {
            $records = $records->with($with);
        }
        if (!empty($this->search)) {
            $term = "%$this->search%";
            return $records->where(function ($query) use ($term) {
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
        return $records;
    }
}
