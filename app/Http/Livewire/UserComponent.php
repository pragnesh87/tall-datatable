<?php

namespace App\Http\Livewire;

use App\Models\Role;
use App\Models\User;
use App\Traits\DataTables;
use Livewire\Component;
use Livewire\WithPagination;
use DB;

class UserComponent extends Component
{
    use WithPagination, DataTables;

    public bool $edit = false;
    public $showModal = false;
    public $user;
    public $userId = null;
    public $searchColumns = ['name', 'email', 'role.name'];
    public $columns = [
        ['key' => 'id', 'value' => 'ID'],
        ['key' => 'name', 'value' => 'Name'],
        ['key' => 'email', 'value' => 'Email'],
        ['key' => 'role', 'value' => 'Role'],
        ['key' => 'post', 'value' => 'Post'],
        ['key' => 'gender', 'value' => 'Gender'],
        ['key' => 'phone', 'value' => 'Phone'],
    ];

    protected $model = User::class;
    protected $relation = ['role', 'posts'];
    protected $listeners = ['deleteSelected', 'exportSelected', 'onConfirmed' => 'deleteRecord'];

    protected function rules()
    {
        if ($this->edit) {
            return [
                'user.name' => ['required'],
                'user.email' => ['required', 'email', 'unique:users,email,' . $this->userId],
                'user.phone' => ['nullable'],
                'user.gender' => ['required'],
                'user.role_id' => ['required'],
            ];
        } else {
            return [
                'user.name' => ['required'],
                'user.email' => ['required', 'email', 'unique:users,email'],
                'user.phone' => ['nullable'],
                'user.gender' => ['required'],
                'user.role_id' => ['required'],
                'user.password' => ['nullable', 'min:6', 'confirmed'],
            ];
        }
    }

    public function mount()
    {
        $this->setPaginationOption([10, 15, 20]);
    }

    public function render()
    {
        return view('livewire.user-component', [
            'users' => $this->users,
            'roles' => Role::all()
        ]);
    }

    public function getUsersProperty()
    {
        return $this->usersQuery->paginate($this->paginate);
    }

    public function getUsersQueryProperty()
    {
        return $this->getQuery();
    }

    public function save()
    {
        $validate = $this->validate();
        $message = '';
        if ($this->edit) {
            $this->user->save();
            $message = 'User updated successfully';
        } else {
            $this->user['password'] = bcrypt($this->user['password']);
            User::create($this->user);
            $message = 'User added successfully';
        }
        $this->dispatchBrowserEvent('showToast', [
            'message' => $message,
            'type' => 'success',
            'title' => 'Success!',
        ]);

        $this->clearAll();
    }

    public function clearAll()
    {
        $this->reset(['user', 'userId', 'edit', 'showModal']);
        $this->resetErrorBag();
    }

    public function edit($editId)
    {
        $this->edit = true;
        $this->userId = $editId;
        $this->user = $this->model::find($editId);
        $this->showModal = true;
    }
}
