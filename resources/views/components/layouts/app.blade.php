<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
    <script src="https://unpkg.com/imask"></script>
</x-layouts.app.sidebar>
