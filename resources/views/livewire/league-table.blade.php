<table class="w-full text-sm text-center">
  <tr class="">
    <th colspan="3">{{ __('Club') }}</th>
    @if($full)
      <th>MP</th>
    @endif
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
  <tr class="border-b border-b-gray-200 last:border-b-0">
    <td class="pt-1">{{ $entry->position }}</td>
    <td class="pt-1"><img src="{{ $entry->team->logo }}" class="w-6 h-6"></td>
    <td class="pt-1 text-left">
      {{ $full ? $entry->team->name : strtoupper($entry->team->short_name) }}
    </td>
    @if($full)
      <td class="pt-1">{{ $entry->mp }}</td>
    @endif
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