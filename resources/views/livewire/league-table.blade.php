<table class="w-full text-sm text-center">
  <tr class="">
    <th colspan="3">{{ __('Club') }}</th>
    <th>MP</th>
    <th>W</th>
    <th>D</th>
    <th>L</th>
    <th>GF</th>
    <th>GA</th>
    <th>GD</th>
    <th>PTS</th>
  </tr>

@foreach($table as $team)
  <tr>
    <td class="pt-1">{{ $loop->index + 1 }}</td>
    <td class="pt-1"><img src="{{ $team['logo'] }}" class="w-6 h-6"></td>
    <td class="pt-1 text-left">{{ $team['name'] }}</td>
    <td class="pt-1">{{ $team['mp'] }}</td>
    <td class="pt-1">{{ $team['w'] }}</td>
    <td class="pt-1">{{ $team['d'] }}</td>
    <td class="pt-1">{{ $team['l'] }}</td>
    <td class="pt-1">{{ $team['gf'] }}</td>
    <td class="pt-1">{{ $team['ga'] }}</td>
    <td class="pt-1">{{ $team['gd'] }}</td>
    <td class="pt-1">{{ $team['pts'] }}</td>
  </tr>
@endforeach
</table>