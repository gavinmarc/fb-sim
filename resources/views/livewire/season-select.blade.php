<div>
  <select wire:model="selected" wire:changed="seasonChanged" class="bg-gray-dark rounded-md pl-3 pr-10 py-2 text-left border-none cursor-pointer focus:outline-none focus:ring-1 focus:ring-yellow-400 focus:border-yellow-400">
    @foreach($seasons as $season)
      <option value="{{ $season->id }}">{{ $season->years }}</option>
    @endforeach
  </select>
</div>