<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}
        <br>
        <div class="mt-4">
            <x-filament::button type="submit">
                Saqlash
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>
