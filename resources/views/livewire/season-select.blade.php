<div>
  <select wire:model="selected" wire:changed="seasonChanged">
    @foreach($seasons as $season)
      <option value="{{ $season->id }}">{{ $season->years }}</option>
    @endforeach
  </select>
</div>