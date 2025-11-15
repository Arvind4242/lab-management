<x-filament-panels::page>
  <h1 class="text-2xl font-bold">Admin Dashboard</h1>
    <p>Welcome to the admin panel!</p>
</x-filament-panels::page>
<x-filament::scripts>
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('element.updated', ({ component }) => {
            // Reset stuck modals
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
