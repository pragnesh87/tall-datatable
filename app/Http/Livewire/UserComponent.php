<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Traits\DataTables;
use Livewire\Component;
use Livewire\WithPagination;
use DB;

class UserComponent extends Component
{
    use WithPagination, DataTables;

    public bool $edit = false;
    public $location;
    public $locationId;
    public $searchColumns = ['name', 'email', 'role.name'];
    public $model = User::class;
    public $columns = [
        ['key' => 'id', 'value' => 'ID'],
        ['key' => 'name', 'value' => 'Name'],
        ['key' => 'email', 'value' => 'Email'],
        ['key' => 'role', 'value' => 'Role'],
        ['key' => 'post', 'value' => 'Post'],
        ['key' => 'gender', 'value' => 'Gender'],
        ['key' => 'phone', 'value' => 'Phone'],
    ];

    public function render()
    {
        return view('livewire.user-component', [
            'users' => $this->users
        ]);
    }

    public function getUsersProperty()
    {
        return $this->usersQuery->paginate($this->paginate);
    }

    public function getUsersQueryProperty()
    {
        return $this->getQuery(['role', 'posts']);
    }

    public function deleteSelected()
    {
        dd($this->selected);
    }
}
