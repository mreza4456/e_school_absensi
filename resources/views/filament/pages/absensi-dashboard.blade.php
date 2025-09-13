<x-filament-panels::page>
    {{ $this->filtersForm }}

    {{-- Stats Overview --}}
    @livewire("App\Filament\Widgets\AdminStaffOrganizationAbsensiOverview", ['filters' => $this->filters])

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="col-span-1">
            @livewire("App\Filament\Widgets\AdminStaffOrganizationAbsensiChart", ['filters' => $this->filters])
        </div>
        <div class="col-span-1">
            @livewire("App\Filament\Widgets\AdminStaffOrganizationMembersLate", ['filters' => $this->filters])
        </div>
    </div>

    @livewire("App\Filament\Widgets\AdminStaffOrganizationAnalisisAbsensi", ['filters' => $this->filters])
    @livewire("App\Filament\Widgets\AdminStaffOrganizationMembersOftenLate", ['filters' => $this->filters])
</x-filament-panels::page>
