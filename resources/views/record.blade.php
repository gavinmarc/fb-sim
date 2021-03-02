<x-app-layout>
  <div class="mx-auto">
    @foreach($recordGroups as $name => $records)
      <div class="mt-6 sm:mt-12 first:mt-0">
        <h1 class="mb-2 text-2xl sm:text-3xl tracking-wide">
          {{ \Str::afterLast($name, "\\") }}
        </h1>
        <div class="flex flex-wrap">
          @foreach($records as $record)
            @php($shade = array_rand(array_flip([600, 700, 800])))
            {{-- TODO: margin --}}
            <div class="w-full sm:w-1/2 lg:w-1/3 h-36 bg-gray-{{ $shade }} m-2 px-4 py-6 rounded-xl"> 
              {{-- record title --}}
              <div class="text-gray-200 text-xs uppercase tracking-wide">{{ $record->title }}</div>

              <div class="flex h-full items-center font-bold">
                {{-- recordable model --}}
                <div class="flex-grow">
                  @if(is_a($record->recordable, 'App\Models\Team'))
                    {{-- team --}}
                    <div class="flex items-center">
                      <img src="{{ $record->recordable->logo }}" class="w-8 h-8 mr-2">
                      <span>{{ $record->recordable->name }}</span>
                    </div>
                  @elseif(is_a($record->recordable, 'App\Models\Season'))
                    {{-- season --}}
                    <div>{{ __('Season') }} {{ $record->recordable->years }}</div>
                  @elseif(is_a($record->recordable, 'App\Models\Fixture'))
                    {{-- fixture --}}
                    <div class="space-y-2">
                      <div class="flex items-center relative">
                        <img src="{{ $record->recordable->homeTeam->logo }}" class="w-6 h-6 mr-2">
                        <span>{{ $record->recordable->homeTeam->name }}</span>
                        <span class="absolute right-0">{{ $record->recordable->home_team_goals }}</span>
                      </div>
                      <div class="flex items-center relative">
                        <img src="{{ $record->recordable->awayTeam->logo }}" class="w-6 h-6 mr-2">
                        <span>{{ $record->recordable->awayTeam->name }}</span>
                        <span class="absolute right-0">{{ $record->recordable->away_team_goals }}</span>
                      </div>
                    </div>
                  @endif
                </div>

                {{-- record value --}}
                <div class="w-16 ml-2 text-right text-3xl my-auto text-yellow-400">{{ $record->value }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
</x-app-layout>