<?php

namespace App\Livewire\Admin;

use App\Models\Campus;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Users extends Component
{
    public $name, $campus_id, $email, $password;

    public $auth, $changePassword = false, $newPassword, $campuses;

    // Editar user
    public $editUser = null;
    public $user = [
        'name' => '',
        'email' => '',
        'campus_id' => ''
    ];

    public function register()
    {
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
        $user->save();

        $this->reset();
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
        $this->validate([
            'user.name' => 'required',
            'user.email' => 'required|email|ends_with:@ubiobio.cl,.ubiobio.cl',
            'user.campus_id' => 'required'
        ]);

        $id = $this->editUser;
        $email = User::where('email', $this->user['email'])->exists();

        if (!$email) {
            $userUpdate = User::find($id);
            $userUpdate->name = $this->user['name'];
            $userUpdate->email = $this->user['email'];
            $userUpdate->campus_id = $this->user['campus_id'];
            $userUpdate->save();

            $this->reset();
            $this->editUser = null;
        } else {
            $this->addError('user.email', 'Email ya registrado.');
        }
    }

    public function setInactive($id)
    {
        $user = User::find($id);
        $user->active = false;
        $user->save();
    }

    public function setActive($id)
    {
        $user = User::find($id);
        $user->active = true;
        $user->save();
    }

    public function close()
    {
        $this->editUser = null;
        $this->changePassword = false;
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

        $this->campuses = Campus::all();

        return view('livewire.admin.users', [
            'users' => $users,
            'auth' => $this->auth,
            'changePassword' => $this->changePassword,
            'campuses' => $this->campuses
        ]);
    }
}
