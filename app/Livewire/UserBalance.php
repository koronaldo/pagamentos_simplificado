<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class UserBalance extends Component
{
    public string $name;
    
    public int $userId;
    
    public float $balance;
    
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->balance = $user->saldo;
    }
    
    public function render(): mixed
    {
        return view('livewire.user-balance');
    }
    
    protected $listeners = [
        'transferSuccess' => 'refreshData'
    ];
    
    public function refreshData(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->balance = $user->saldo;
    }
    
}
