<div class="flex flex-col">
    <div class="flex flex-row gap-2 items-center justify-end">
        <x-zondicon-notification class="size-6" />
        <img class="size-10" src="{{ asset('img/man.png') }}" alt="">
    </div>
    <div class="mt-15">
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                <svg class="shrink-0 size-4 text-black" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <input wire:model.live.debounce.200ms="query"
                class="py-2.5 sm:py-3 px-10 block w-full border border-black rounded-lg
                 sm:text-sm focus:border-blue-500  disabled:pointer-events-none
                  bg-white  text-[#000000CC] placeholder-[#000000B3]
                   focus:ring-neutral-600"
                type="text" aria-expanded="false" placeholder="Search your location">
            @if (!empty($suggestions))
                <div class="absolute z-10 bg-white border border-gray-200 rounded w-full mt-1 shadow">
                    @foreach ($suggestions as $s)
                        <div class="p-2 hover:bg-gray-100 cursor-pointer"
                            wire:click="setLocation('{{ $s['lat'] }}','{{ $s['lon'] }}','{{ $s['name'] }}')">
                            {{ $s['name'] }}
                            @if (isset($s['state']))
                                , {{ $s['state'] }}
                            @endif
                            , {{ $s['country'] }}
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
    <div>

    </div>
    <div class="mt-10">
        @if ($weather)
            <div class="mt-4 p-4 bg-[#ffffff] border border-black rounded shadow">
                <p class="text-xl">Current condition</p>
                <div class="flex flex-row items-start justify-between mt-8">
                    <div class="flex flex-col">
                        <p class="text-3xl">{{ $weather['main']['temp'] }}°C</p>
                        <p>Feels like {{ $weather['main']['feels_like'] }}°C</p>
                    </div>
                    <div>
                        <img class="size-18" src=" https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png"
                            alt="">
                    </div>
                </div>
                <div class="flex flex-col gap-2 mt-10">
                    <p>Temp: {{ $weather['main']['temp'] }}°C</p>
                    <p>Humidity: {{ $weather['main']['humidity'] }}%</p>
                    <p>Wind: {{ $weather['wind']['speed'] }}km/h</p>
                </div>
            </div>
        @endif
    </div>
</div>
