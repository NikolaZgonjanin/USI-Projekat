<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dostupni firmveri
        </h2>
    </x-slot>

    <div class="py-10 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-100 border border-green-300 px-4 py-2 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                Lista projekata i najnovijih verzija firmvera
            </h3>

            @can('create', App\Models\Project::class)
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                    Dodaj novi projekat
                </a>
            @endcan
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <div class="grid grid-cols-12 px-4 py-3 bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
                <div class="col-span-5">Projekat</div>
                <div class="col-span-2">Verzija</div>
                <div class="col-span-3">Poslednje ažuriranje</div>
                <div class="col-span-2 text-right">Prijave grešaka</div>
            </div>

            @forelse ($projects as $project)
                @php
                    $latestFirmware = $project->firmwareVersions->first();
                    $bugCount = $project->support_requests_count ?? 0;
                @endphp
                <a href="{{ route('projects.show', $project) }}"
                   class="block border-t border-gray-200 hover:bg-gray-50 transition">
                    <div class="grid grid-cols-12 px-4 py-3 items-center text-sm text-gray-900">
                        <div class="col-span-5">
                            <span class="font-semibold text-indigo-700">{{ $project->name }}</span>
                            <span class="ml-2 text-xs text-gray-500">{{ $project->code }}</span>
                        </div>
                        <div class="col-span-2">
                            {{ $latestFirmware?->version ?? 'Nema verzije' }}
                        </div>
                        <div class="col-span-3">
                            @if ($latestFirmware)
                                {{ optional($latestFirmware->released_at ?? $latestFirmware->created_at)->diffForHumans() }}
                            @else
                                <span class="text-gray-400">Nema ažuriranja</span>
                            @endif
                        </div>
                        <div class="col-span-2 text-right">
                            @if ($bugCount === 0)
                                <span class="text-xs text-green-600">Nema</span>
                            @else
                                <span class="text-xs font-semibold {{ $bugCount > 5 ? 'text-red-600' : 'text-orange-500' }}">
                                    {{ $bugCount }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-6 text-center text-sm text-gray-500">
                    Nema projekata.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

