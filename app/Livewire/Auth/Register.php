<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('สมัครสมาชิก')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $data = $this->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create($data + ['role' => 'customer']);
        Auth::login($user);
        session()->regenerate();

        return $this->redirectIntended(route('home'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
