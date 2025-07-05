<x-filament::page>
        {{-- <h1>{{ $this->getTitle() }}</h1> --}}
    <form wire:submit.prevent="save" class="space-y-6">
        {{ $this->form }}
        
        <x-filament::button type="submit">
            {{ __('Save') }}
        </x-filament::button>
    </form>
</x-filament::page>