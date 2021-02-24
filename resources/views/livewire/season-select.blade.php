<div>
  <select wire:model="selected">
    @foreach($seasons as $season)
      <option value="{{ $season->id }}">{{ $season->years }}</option>
    @endforeach
  </select>
</div>