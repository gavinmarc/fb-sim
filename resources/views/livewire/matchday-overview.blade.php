<div>
  <div class="text-sm">
    @foreach($fixtures as $fixture)
      <div class="flex py-2 items-center border-b border-b-gray-200 last:border-b-0">
        {{-- Teams --}}
        <div class="flex-grow space-y-1">
          <div class="flex items-center">
            <img class="w-5 h-5" src="{{ $fixture->homeTeam->logo }}">
            <span class="ml-1">{{ $fixture->homeTeam->name }}</span>
          </div>
          <div class="flex items-center">
            <img class="w-5 h-5" src="{{ $fixture->awayTeam->logo }}">
            <span class="ml-1">{{ $fixture->awayTeam->name }}</span>
          </div>
        </div>
        {{-- Result --}}
        <div class="mr-2">
          <button class="w-8 py-1 rounded-md font-semibold bg-blue-50">1,50</button>
          <button class="w-8 py-1 rounded-md font-semibold bg-blue-50">1,50</button>
          <button class="w-8 py-1 rounded-md font-semibold bg-blue-50">1,50</button>
        </div>
        {{-- Over/Under --}}
        <div>
          <button class="w-8 py-1 rounded-md font-semibold bg-yellow-50">1,50</button>
          <button class="w-8 py-1 rounded-md font-semibold bg-yellow-50">1,50</button>
        </div>
      </div>
    @endforeach
  </div>
</div>
