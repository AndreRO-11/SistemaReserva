<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class ResetPassword extends Component
{
    public $email;
    public $token;
    public $password;
    public $password_confirmation;

    protected $messages = [
        'email.required' => 'El campo de correo es olbigatorio.',
        'email.email' => 'Ingrese un correo válido.',
        'password.min' => 'Mínimo 6 caracteres.',
        'password.required' => 'El campo contraseña es obligatorio.',
        'password.same' => 'Las contraseñas no coinciden.',
        'password_confirmation.min' => 'Mínimo 6 caracteres.',
        'password_confirmation.required' => 'El campo contraseña es obligatorio.',
        'password_confirmation.same' => 'Las contraseñas no coinciden.',
    ];

    public function resetPassword()
    {
        $this->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->password = $this->password;
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->dispatch('success', 'Contraseña actualizada.');
            return $this->redirect('/login');
        } else {
            $this->dispatch('failed', 'Error en datos.');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
