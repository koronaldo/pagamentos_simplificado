<x-layouts.app :title="__('Dashboard')">
<div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
	<div class="grid h-screen gap-1 md:grid-cols-2">
		<!-- Primeira coluna dividida em duas partes verticalmente -->
		<div
			class="relative h-full overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 flex flex-col">
			<div
				class="flex-1 border-b border-neutral-200 dark:border-neutral-700">
				<!-- Parte de cima -->
				<livewire:user-info />
			</div>
			<div class="flex-1">
				<livewire:user-balance />
			</div>
		</div>

		<!-- Segunda coluna com transaction-list -->
		<div
			class="relative h-full overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
			<livewire:transaction-list />
			
		</div>
	</div>
</div>
</x-layouts.app>