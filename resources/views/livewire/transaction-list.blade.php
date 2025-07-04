<?php
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

new #[Layout('components.layouts.transation-list')] class() extends Component {

    public string $name;

    public int $userId;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->userId = $user->id;
        $this->balance = $user->saldo;

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

    protected $listeners = [
        'listTransactions' => 'mount'
    ];
}?>

<div class="user-info">


	<form wire:submit="transfer">

		<div class="user-info">
			<h2 class="text-center font-bold text-gray-700 text-xl my-4">10
				últimas transações:</h2>

			<table class="table-auto w-full border mt-4 text-sm">
				<thead>
					<tr class="bg-gray-100">
						<th class="px-4 py-2 text-left">Status</th>
						<th class="px-4 py-2 text-left">Valor</th>
						<th class="px-4 py-2 text-left">Data</th>
						<th class="px-4 py-2 text-left">Favorecido/Pagador</th>
					</tr>
				</thead>
				<tbody>
					@forelse ($transactions as $transaction) @php $isSender =
					$transaction->sender_id == Auth::id(); $status = $isSender ?
					'Envio' : 'Recebimento'; $rowClass = $isSender ? 'bg-red-50' :
					'bg-green-50'; // cor de fundo por tipo @endphp

					<tr class="border-t {{ $rowClass }}">
						<td class="px-4 py-2 text-gray-700 font-semibold">{{ $status }}</td>
						<td class="px-4 py-2 text-gray-900">R$ {{
							number_format($transaction->amount, 2, ',', '.') }}</td>
						<td class="px-4 py-2 text-gray-600">{{
							$transaction->created_at->format('d/m/Y H:i') }}</td>
						<td class="px-4 py-2 text-gray-900">{{
							$transaction->receiver_id == auth()->id() 
        					? ($transaction->sender->name ?? 'Desconhecido') 
        					: ($transaction->sender_id == auth()->id() 
            				? ($transaction->receiver->name ?? 'Desconhecido') 
            				: 'Desconhecido') 
            				}}
						</td>
					</tr>
					@empty
					<tr>
						<td colspan="6" class="px-4 py-2 text-center text-gray-500">Nenhuma
							transação encontrada.</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>


	</form>
</div>

