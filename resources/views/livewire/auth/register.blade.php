<?php
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class() extends Component {

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $cpfcnpj = '';
    public string $perfil = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Rules\Password::defaults()
            ],
            'cpfcnpj' => [
                'required',
                'string',
                'unique:' . User::class
            ],
            'perfil' => [
                'required',
                'in:lojista,cliente'
            ]
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // o único banco que dá dinheiro
        $validated['saldo'] = 1000;

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
};
?>

<div class="flex flex-col gap-6">
	<x-auth-header :title="__('Criar uma conta')"
		:description="__('Informe dados do usuário para registrar uma conta')" />

	<!-- Session Status -->
	<x-auth-session-status class="text-center" :status="session('status')" />

	<form wire:submit="register" class="flex flex-col gap-6">
		<!-- Name -->
		<flux:input wire:model="name" :label="__('Nome')" type="text" required
			autofocus autocomplete="name" :placeholder="__('Nome completo')" />

		<!-- Email Address -->
		<flux:input wire:model="email" :label="__('Email')" type="email"
			required autocomplete="email" placeholder="email@example.com" />


		<!-- CPF/CNPJ 
		<flux:input wire:model="cpfcnpj" :label="__('CPF/CNPJ')" type="text"
			required autocomplete="cpfcnpj"
			:placeholder="__('Entre com cpf ou cnpj')" />
-->
		<flux:input wire:model="cpfcnpj" name="cpfcnpj" x-data
			x-init="aplicarMascara($el)" :label="__('CPF/CNPJ')" type="text"
			required autocomplete="cpfcnpj"
			:placeholder="__('Entre com cpf ou cnpj')" />



		<!-- Perfil -->
		<flux:select wire:model="perfil" :label="__('Perfil')"
			:placeholder="__('Perfil')" required>
			<flux:select.option value="lojista">Lojista</flux:select.option>
			<flux:select.option value="cliente">Cliente</flux:select.option>
		</flux:select>

		<!-- Password -->
		<flux:input wire:model="password" :label="__('Senha')" type="password"
			required autocomplete="new-password" :placeholder="__('Senha')"
			viewable />

		<!-- Confirm Password -->
		<flux:input wire:model="password_confirmation"
			:label="__('Confirmar senha')" type="password" required
			autocomplete="new-password" :placeholder="__('Confirmar senha')"
			viewable />

		<div class="flex items-center justify-end">
			<flux:button type="submit" variant="primary" class="w-full">
                {{ __('Criar usuário') }}
            </flux:button>
		</div>
	</form>

	<div
		class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
		{{ __('Já possui um conta?') }}
		<flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
	</div>
	<script
		src="https://cdn.jsdelivr.net/npm/inputmask/dist/inputmask.min.js"></script>
	<script src="https://unpkg.com/imask"></script>
	<script>
    function aplicarMascara(el) {
        // Espera até que IMask esteja disponível
        if (typeof IMask === 'undefined') {
            document.addEventListener('imask:ready', () => aplicarMascara(el));
            return;
        }

        IMask(el, {
            mask: [
                {
                    mask: '000.000.000-00',
                    maxLength: 11
                },
                {
                    mask: '00.000.000/0000-00'
                }
            ]
        });
    }

    // Gatilho de evento se IMask ainda não estiver disponível
    if (typeof IMask === 'undefined') {
        const script = document.querySelector('script[src*="imask"]');
        if (script) {
            script.addEventListener('load', () => {
                document.dispatchEvent(new Event('imask:ready'));
            });
        }
    }
</script>
</div>
