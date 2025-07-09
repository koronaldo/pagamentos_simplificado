<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    public string $perfil = '';
    public string $saldo = '';
    public string $cpfcnpj = '';
    

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->perfil = Auth::user()->perfil;
        $this->saldo = Auth::user()->saldo;
        $this->cpfcnpj = Auth::user()->cpfcnpj;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpfcnpj' => ['required', 'string'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
             
            ],
        ]);
       // dd($this->cpfcpnj);
        $this->validateCpfCnpj($validated['cpfcnpj']);
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }
    
    
    private function validateCpfCnpj(string $cpfcnpj): void
    {
        $cpfcnpj = preg_replace('/\D/', '', $cpfcnpj); // Remove non-numeric characters
        
        if (strlen($cpfcnpj) === 11) {
            // Validate CPF
            if (!$this->isValidCpf($cpfcnpj)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cpfcnpj' => __('CPF inválido.'),
                ]);
            }
        } elseif (strlen($cpfcnpj) === 14) {
            // Validate CNPJ
            if (!$this->isValidCnpj($cpfcnpj)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'cpfcnpj' => __('CNPJ inválido.'),
                ]);
            }
        } else {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'cpfcnpj' => __('CPF/CNPJ inválido. Deve conter 11 ou 14 dígitos.'),
            ]);
        }
    }
    
    
    private function isValidCpf(string $cpf): bool
    {
        // Check for invalid sequences
        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        
        // Calculate verification digits
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
    }
    
    private function isValidCnpj(string $cnpj): bool
    {
        // Check for invalid sequences
        if (preg_match('/^(\d)\1{13}$/', $cnpj)) {
            return false;
        }
        
        // Calculate verification digits
        $weights = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        for ($t = 12; $t < 14; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cnpj[$c] * $weights[$c];
            }
            $d = $d % 11 < 2 ? 0 : 11 - ($d % 11);
            if ($cnpj[$c] != $d) {
                return false;
            }
            array_unshift($weights, 6); // Adjust weights for the second digit
        }
        
        return true;
    }
    

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Atualize seus dados')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Nome')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />
                
                               
                <flux:input wire:model="saldo" :label="__('Saldo')" type="text" required autocomplete="saldo" />
                
                
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

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Salvar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
