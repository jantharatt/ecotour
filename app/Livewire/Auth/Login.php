<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('เข้าสู่ระบบ')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function login()
    {
        $this->validate();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');

            return;
        }

        session()->regenerate();
        $default = Auth::user()->isAdmin() ? route('admin.dashboard') : route('home');

        return $this->redirectIntended($default, navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
