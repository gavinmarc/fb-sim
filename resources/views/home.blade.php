<x-app-layout>
  <div class="flex flex-wrap lg:flex-nowrap lg:flex-row lg:space-x-4">
    <x-card title="{{ __('League Standings') }}" class="w-full order-last sm:order-none sm:flex-1 sm:mr-2 lg:flex-none lg:w-1/4">
      <livewire:league-table :full="false">
    </x-card>
    <x-card title="{{ __('Fixtures') }}" class="w-full lg:flex-grow order-first lg:order-none">
      <livewire:matchday-overview>
    </x-card>
    <x-card title="{{ __('Bets') }}" class="w-full sm:flex-1 sm:ml-2 lg:flex-none lg:w-1/4">
      
    </x-card>
  </div>
</x-app-layout>