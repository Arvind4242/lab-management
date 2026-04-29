<x-filament-panels::page>
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="flex-1">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Welcome back, {{ auth()->user()->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->format('l, d F Y') }} &mdash; Here's a snapshot of your lab activity.
                </p>
            </div>
            @if(auth()->user()->role === 'admin')
                <span class="inline-flex items-center gap-1.5 rounded-full bg-pink-100 px-3 py-1 text-xs font-medium text-pink-700 dark:bg-pink-900/30 dark:text-pink-300">
                    <x-heroicon-m-shield-check class="h-3.5 w-3.5" />
                    Admin
                </span>
            @endif
        </div>
    </div>
</x-filament-panels::page>

<x-filament::scripts>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('element.updated', ({ component }) => {
            if (component?.serverMemo?.data?.mountedFormComponentActions?.length > 0) {
                component.serverMemo.data.mountedFormComponentActions = [];
            }
            if (component?.serverMemo?.data?.mountedTableActions?.length > 0) {
                component.serverMemo.data.mountedTableActions = [];
            }
        });
    });
</script>
</x-filament::scripts>
