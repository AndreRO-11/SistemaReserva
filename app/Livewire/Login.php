<?php

namespace App\Livewire;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class Login extends Component
{
    public $registerForm = false;

    public $forgotPassword = false;
    public $email;

    // Atributos para login
    public $user = [
        'name' => '',
        'email' => '',
        'password' => ''
    ];

    // Atributos para register
    public $passwordConfirmed, $name, $city;

    protected $messages = [
        'user.email' => 'Credenciales no válidas.',
        'email.required' => 'El campo de correo es olbigatorio.',
        'email.email' => 'Ingrese un correo válido.',
    ];

    public function register()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users|ends_with:@ubiobio.cl,.ubiobio.cl',
            'password' => 'required|confirmed',
            'passwordConfirmed' => 'required',
            'city' => 'required'
        ]);

        if ($this->password == $this->passwordConfirmed) {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'city' => $this->city
            ]);
        }
    }

    public function login(Request $request)
    {
        $this->validate([
            'user.email' => 'required|email',
            'user.password' => 'required'
        ]);

        $user = User::where('email', $this->user['email'])->where('active', true)->first();

        $credentials = [
            'email' => $this->user['email'],
            'password' => $this->user['password'],
        ];

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->to('/');
            }
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->to('/');
    }

    public function sendPasswordResetLink()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('success', 'Enlace enviado.');
        } else {
            $this->addError('email', __($status));
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function toggleRegisterForm()
    {
        $this->registerForm = !$this->registerForm;
    }

    public function render()
    {
        if (Auth::check()) {
            return $this->redirect('/');
        }
        return view('livewire.login', [
            'registerForm' => $this->registerForm,
        ]);
    }
}
