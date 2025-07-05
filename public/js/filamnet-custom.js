setTimeout(() => {
    Livewire.on('refreshInstallmentsRelationManager', () => {
        const relationManager = document.querySelector(`[wire\\:id].fi-resource-relation-manager`);

        if (relationManager) {
            const livewireComponent = Livewire.find(relationManager.getAttribute('wire:id'));
            if (livewireComponent) {
                livewireComponent.$refresh();
            }
        }
    });
}, 500)
