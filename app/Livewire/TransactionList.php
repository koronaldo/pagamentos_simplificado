<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\Transaction;

class TransactionList extends Component
{
      
    public string $name;
    public int $userId;
    
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->userId = $user->id;
        //$this->balance = $user->saldo;
        
        $this->transactions = Transaction::
        with('receiver')-> // carrega o user do receiver_id
        with('sender')-> // carrega o user do Sender_id
        where('sender_id', $user->id)
        ->orWhere('receiver_id', $user->id)
        ->orderByDesc('created_at')
        ->take(10)
        ->get();
    }
    
    public function render(): mixed
    {
        return view('livewire.transaction-list', [
            'transactions' => $this->transactions
        ]);
    }
    
   /// public function render()
    //{
    //    dd('passei aqui');
    //    return view('livewire.transaction-list');
   // }
    
    protected $listeners = [
        'listTransactions' => 'mount'
    ];
    
}
