<div>
    <x-filament::dropdown>
        <x-slot name="trigger">
            <button type="button" class="flex items-center gap-2 px-3 py-2 text-sm font-medium">
                <x-heroicon-o-globe-alt class="w-5 h-5"/>
                {{ strtoupper(app()->getLocale()) }}
            </button>
        </x-slot>

        <x-filament::dropdown.list>
            <x-filament::dropdown.list.item
                icon="heroicon-o-flag"
                :href="route('lang.switch', 'en')"
            >
                English
            </x-filament::dropdown.list.item>

            <x-filament::dropdown.list.item
                icon="heroicon-o-flag"
                :href="route('lang.switch', 'id')"
            >
                Bahasa Indonesia
            </x-filament::dropdown.list.item>
        </x-filament::dropdown.list>
    </x-filament::dropdown>
</div>
