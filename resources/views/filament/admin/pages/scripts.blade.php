<script>
    document.addEventListener('livewire:navigated', () => {
        // Close all Filament modals after Livewire refresh
        document.querySelectorAll('[x-data][x-show]').forEach((modal) => {
            try {
                modal.__x.$data.isOpen = false;
            } catch (e) {}
        });
    });

    document.addEventListener('livewire:update', () => {
        // Also close when component updates after filter/search
        document.dispatchEvent(new CustomEvent('close-modal'));
    });
</script>
