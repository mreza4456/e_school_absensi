<x-filament-panels::page>
    {{ $this->filtersForm }}

    {{-- Stats Overview --}}
    @livewire("App\Filament\Widgets\AdminStaffSekolahAbsensiOverview", ['filters' => $this->filters])

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="col-span-1">
            @livewire("App\Filament\Widgets\AdminStaffSekolahAbsensiChart", ['filters' => $this->filters])
        </div>
        <div class="col-span-1">
            @livewire("App\Filament\Widgets\AdminStaffSekolahSiswaTerlambat", ['filters' => $this->filters])
        </div>
    </div>

    @livewire("App\Filament\Widgets\AdminStaffSekolahAnalisisAbsensi", ['filters' => $this->filters])
    @livewire("App\Filament\Widgets\AdminStaffSekolahSiswaSeringTerlambat", ['filters' => $this->filters])
</x-filament-panels::page>
