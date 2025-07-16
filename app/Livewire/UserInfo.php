<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
//use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class UserInfo extends Component
{
    
    public string $name;
    
    public int $userId;
    
    public float $balance;
    
    public string $recipientCpfCnpj = '';
    
    public string $recipientId = '0';
    
    
    public float $transferAmount = 0.00;
    
    public $users = [];
    
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->balance = $user->saldo;
        
        $this->users = User::where('id', '!=', Auth::id())->get();
        //
    }
    
    private function senddata($url, $data)
    {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        
        //return 1;
    }
    
    public function transfer(): void
    {
        DB::transaction(function () {
            
            
            
            //$dest = User::where('cpfcnpj', $this->recipientCpfCnpj)->first();
            $dest = User::where('id', $this->recipientId)->first();
            
            if (! $dest) {
                session()->flash('error', __('Recebedor não encontrado.'));
                return;
            }
            
            $origin = Auth::user();
            
            $logist = User::where('cpfcnpj', $origin->cpfcnpj)->where('perfil', 'lojista')->first();
            if ($logist) {
                session()->flash('error', __('Logista não pode transferir dinheiro'));
                return;
            }
            
            if ($origin->cpfcnpj == $dest->cpfcnpj) {
                session()->flash('error', __('Tranferência para mesma pessoa, não permitido.'));
                return;
            }
            
            if ($origin->saldo < $this->transferAmount) {
                session()->flash('error', __('Saldo insuficiente para transferência.'));
                return;
            }
            
            $data = [
                'origin' => [
                    'cpfcnpj' => $origin->cpfcnpj,
                    'name' => $origin->name,
                    'balance' => $origin->saldo
                ],
                'destination' => [
                    'cpfcnpj' => $dest->cpfcnpj,
                    'name' => $dest->name,
                    'balance' => $dest->saldo
                ],
                'amount' => $this->transferAmount
            ];
            
            $response = $this->senddata('https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/auth', $data);
            if ($response === false) {
                session()->flash('error', __('Falha na transferência'));
                return;
            } else {
                // echo 'Response: ' . $response;
                session()->flash('success', __('Transação realizada com sucesso'));
            }
            
            $origin->saldo -= $this->transferAmount;
            $origin->save();
            
            $dest->saldo += $this->transferAmount;
            $dest->save();
            
            $transaction = new Transaction();
            $transaction->sender_id = $origin->id;
            $transaction->receiver_id = $dest->id;
            $transaction->amount = $this->transferAmount;
            $transaction->save();
            
            $data = [
                'to' => $origin->email,
                'message' => "Você enviou R$ {$this->transferAmount} para {$dest->name}"
                ];
            $this->senddata('https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/send', $data);
            
            $data = [
                'to' => $dest->email,
                'message' => "Você recebeu R$ {$this->transferAmount} de {$origin->name}"
                ];
            $this->senddata('https://66ad1f3cb18f3614e3b478f5.mockapi.io/v1/send', $data);
            
            $this->dispatch('transferSuccess');
            $this->dispatch('listTransactions');
        });
    }
    
    public function render(): mixed
    {
        return view('livewire.user-info');
    }
    
}
