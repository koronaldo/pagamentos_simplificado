<?php
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

new #[Layout('components.layouts.user-info')] class() extends Component {

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
}?>
<div class="user-balance-view flex items-center justify-center h-full">
	<div class="text-center">
		<p class="text-xl font-semibold text-gray-700">{{ __('Saldo atual:')
			}}</p>
		<p class="text-4xl text-blue-500 font-bold">{{ number_format($balance,
			2, ',', '.') }}</p>
	</div>
</div>

