<table class="w-full text-sm text-center">
  <tr class="">
    <th colspan="3">{{ __('Club') }}</th>
    <th>MP</th>
    <th>W</th>
    <th>D</th>
    <th>L</th>
    @if($full)
      <th>GF</th>
      <th>GA</th>
    @endif
    <th>GD</th>
    <th>PTS</th>
  </tr>

@foreach($table->entries as $entry)
  <tr>
    <td class="pt-1">{{ $entry->position }}</td>
    <td class="pt-1"><img src="{{ $entry->team->logo }}" class="w-6 h-6"></td>
    <td class="pt-1 text-left">{{ $entry->team->name }}</td>
    <td class="pt-1">{{ $entry->mp }}</td>
    <td class="pt-1">{{ $entry->w }}</td>
    <td class="pt-1">{{ $entry->d }}</td>
    <td class="pt-1">{{ $entry->l }}</td>
    @if($full)
      <td class="pt-1">{{ $entry->gf }}</td>
      <td class="pt-1">{{ $entry->ga }}</td>
    @endif
    <td class="pt-1">{{ $entry->gd }}</td>
    <td class="pt-1">{{ $entry->pts }}</td>
  </tr>
@endforeach
</table>