<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserComponent extends Component
{
    use WithPagination;

    public $paginate = 10;

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
        return User::query();
    }
}
