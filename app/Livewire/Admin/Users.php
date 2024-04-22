<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    public $name, $campus, $city, $email, $password;

    public $auth, $changePassword = false, $newPassword;

    // Editar user
    public $editUser = null;
    public $user = [
        'name' => '',
        'email' => '',
        'city' => ''
    ];

    public function edit($id)
    {
        $this->changePassword = false;
        $this->editUser = $id;
        $user = User::find($id);

        $this->user['name'] = $user->name;
        $this->user['email'] = $user->email;
        $this->user['campus'] = $user->campus;
        $this->user['city'] = $user->city;
    }

    public function update()
    {
        $this->validate([
            'user.name' => 'required',
            'user.email' => 'required|email|ends_with:@ubiobio.cl,.ubiobio.cl',
            'user.campus' => 'required',
            'user.city' => 'required'
        ]);

        $campus = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'], $this->user['campus'])));

        User::find($this->editUser)->update([
            'name'=> $this->user['name'],
            'email'=> $this->user['email'],
            'campus'=> $campus,
            'city'=> $this->user['city']
        ]);

        $this->reset();
        $this->editUser = null;
    }

    public function setInactive($id)
    {
        $user = User::find($id);
        $user->update([
            'active' => false
        ]);
    }

    public function setActive($id)
    {
        $user = User::find($id);
        $user->update([
            'active' => true
        ]);
    }

    public function close()
    {
        $this->editUser = null;
    }

    public function register()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users|ends_with:@ubiobio.cl,.ubiobio.cl',
            'password' => 'required|min:6',
            'campus' => 'required',
            'city' => 'required'
        ]);

        //$campus = strtoupper(str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'], $this->campus));
        $campus = strtoupper(preg_replace('/[^a-zA-Z0-9ñÑ\s]/', '', str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], ['A', 'E', 'I', 'O', 'U', 'A', 'E', 'I', 'O', 'U'], $this->campus)));

        $user = new User();

        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = $this->password;
        $user->campus = $campus;
        $user->city = $this->city;
        $user->remember_token = Str::random(32);

        $user->save();

        $this->reset();
    }

    public function toggleChangePassword()
    {
        $this->changePassword = !$this->changePassword;
    }

    public function setPassword()
    {
        $this->validate([
            'newPassword'=> 'required|min:6',
        ]);
        $user = Auth::user();
        $user->password = $this->newPassword;
        $user->save();

        Auth::attempt(['email' => $user->email, 'password' => $this->newPassword]);

        $this->toggleChangePassword();
        $this->reset();
    }

    public function render()
    {
        $users = User::all();
        $this->auth = Auth::user();

        return view('livewire.admin.users', [
            'users' => $users,
            'auth' => $this->auth,
            'changePassword' => $this->changePassword
        ]);
    }
}
