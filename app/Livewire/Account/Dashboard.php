<?php

namespace App\Livewire\Account;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('คำสั่งซื้อของฉัน')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.account.dashboard', [
            'orders' => Auth::user()->orders()->withCount('items')->latest()->get(),
        ]);
    }
}
