<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Users extends Component
{
    public $name, $campus_id, $email, $password;

    public $auth, $changePassword = false, $newPassword, $campuses, $activeFilter = false;

    use WithPagination;

    // Editar user
    public $editUser = null;
    public $user = [
        'name' => '',
        'email' => '',
        'campus_id' => ''
    ];

    protected $messages = [
        'name.required' => 'El campo de nombre es obligatorio.',
        'email.unique' => 'Email ya registrado.',
        'email.required' => 'El campo de email es obligatorio.',
        'user.email.ends_with' => 'El email debe terminar en @...ubiobio.cl',
        'password.required' => 'El campo contraseña es obligatorio.',
        'password.min' => 'Mínimo 6 caracteres.',
        'campus_id.required' => 'Seleccione una sede.',
    ];

    public function register()
    {
        $this->changePassword = false;
        $this->editUser = null;

        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users|ends_with:@ubiobio.cl,.ubiobio.cl',
            'password' => 'required|min:6',
            'campus_id' => 'required'
        ]);

        $user = new User();

        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->campus_id = $this->campus_id;
        $user->remember_token = Str::random(32);

        if ($user->save()) {
            $this->reset();
            $this->dispatch('success', 'Usuario agregado correctamente.');
        } else {
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function edit($id)
    {
        $this->changePassword = false;
        $this->editUser = $id;
        $user = User::find($id);

        $this->user['name'] = $user->name;
        $this->user['email'] = $user->email;
        $this->user['campus_id'] = $user->campus->id;
    }

    public function update()
    {
        $id = $this->editUser;

        $this->validate([
            'user.name' => 'required',
            'user.email' => 'required|email|unique:users,email,' . $id . '|ends_with:@ubiobio.cl,.ubiobio.cl',
            'user.campus_id' => 'required'
        ]);

        $userUpdate = User::find($id);

        $userUpdate->name = $this->user['name'];
        $userUpdate->email = $this->user['email'];
        $userUpdate->campus_id = $this->user['campus_id'];

        if ($userUpdate->save()) {
            $this->reset();
            $this->editUser = null;
            $this->dispatch('success', 'Usuario actualizado correctamente.');
        } else {
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function setInactive($id)
    {
        $user = User::find($id);
        $user->active = false;
        $user->save();
        $this->resetPage();
        $this->dispatch('warning', 'Usuario desactivado.');
    }

    public function setActive($id)
    {
        $user = User::find($id);
        $user->active = true;
        $user->save();
        $this->resetPage();
        $this->dispatch('success', 'Usuario activado.');
    }

    public function close()
    {
        $this->editUser = null;
        $this->changePassword = false;
        $this->reset();
        $this->resetPage();
    }


    public function toggleChangePassword()
    {
        $this->changePassword = !$this->changePassword;
    }

    public function setPassword()
    {
        try {
            $this->validate([
                'newPassword' => 'required|min:6',
            ]);

            Auth::user()->password = $this->newPassword;
            Auth::attempt(['email' => Auth::user()->email, 'password' => $this->newPassword]);

            $this->toggleChangePassword();
            $this->reset();

            $this->dispatch('success', 'Contraseña actualizada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('newPassword', 'Contraseña obligatoria / Mínimo 6 caracteres.');
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function filterByActive()
    {
        $this->activeFilter = !$this->activeFilter;
        $this->resetPage();
    }

    public function render()
    {
        sleep(1);

        $allUsers = User::all();
        $allUsers = User::query();

        if (!$this->activeFilter) {
            $allUsers->where('active', true);
        }

        $this->auth = Auth::user();

        $this->campuses = Campus::all();

        // ORDEN
        $allUsers = $allUsers->orderBy('active', 'desc')
            ->orderBy('name', 'asc');

        $users = $allUsers->paginate(10);

        return view('livewire.admin.users', [
            'users' => $users,
            'auth' => $this->auth,
            'changePassword' => $this->changePassword,
            'campuses' => $this->campuses
        ]);
    }
}
