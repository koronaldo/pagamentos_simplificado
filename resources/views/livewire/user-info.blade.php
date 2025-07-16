<div class="user-info">
	<h2 class="user-name text-gray-500">{{ $name }}</h2>

	<form wire:submit="transfer">
		<!-- CPF/CNPJ Field -->

		 <!-- 
		<flux:input wire:model="recipientCpfCnpj" name="recipientCpfCnpj"
			x-data x-init="aplicarMascara($el)" :label="__('CPF/CNPJ')"
			type="text" required autocomplete="cpfcnpj"
			:placeholder="__('Entre com cpf ou cnpj do receptor')" />
         -->
         
		<flux:select wire:model="recipientId" name="recipientId"
			:label="__('Selecione o receptor')" required>

			<option value="">{{ __('Selecione um receptor') }}</option>

    		@foreach ($users as $user)
        		<option value="{{ $user->id }}">{{ $user->name }} ({{
				$user->cpfcnpj }})</option>
    		@endforeach
		</flux:select>

		<!-- Transfer Amount Field -->
		<flux:input wire:model="transferAmount"
		
			:label="__('Valor de transferência')" type="number" required
			min="0.01" step="0.01"
			:placeholder="__('Informe o valor de transferência')" />

		
		
		@if (session('error'))
    		<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        		{{ session('error') }}
    		</div>
		@endif

		@if (session('success'))
    		<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        		{{ session('success') }}
    		</div>
		@endif

		@if (session('info'))
    		<div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
        		{{ session('info') }}
    		</div>
		@endif
		

		<!-- Submit Button -->
		<flux:button type="submit" class="mt-4">
            {{ __('Transferir') }}
        </flux:button>

	</form>

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

