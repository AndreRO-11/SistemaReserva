<?php

namespace App\Livewire;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email, $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->to('/');
        }
        $this->addError('email', 'Invalid credentials');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->to('/');
    }

    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     $request->session()->invalidate();
    //     $request->session()->regenerateToken();
    //     return $this->redirect('/');
    // }

    public function render()
    {
        if(Auth::check()) {
            return $this->redirect('/');
        }
        return view('livewire.login');
    }
}
