
<div class="user-balance-view flex items-center justify-center h-full">
	<div class="text-center">
		<p class="text-xl font-semibold text-gray-700">{{ __('Saldo atual:')
			}}</p>
		<p class="text-4xl text-blue-500 font-bold">{{ number_format($balance,
			2, ',', '.') }}</p>
	</div>
</div>

