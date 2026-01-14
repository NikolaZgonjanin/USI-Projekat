<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projekat: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-2">Osnovne informacije</h3>
            <p><span class="font-semibold">Šifra:</span> {{ $project->code }}</p>
            <p class="mt-2">
                <span class="font-semibold">Opis:</span><br>
                {{ $project->description ?: 'Nema opisa.' }}
            </p>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Verzije firmvera</h3>
                @can('create', App\Models\FirmwareVersion::class)
                    <a href="{{ route('firmware-versions.create', ['project_id' => $project->id]) }}"
                       class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-semibold rounded-md hover:bg-indigo-700">
                        Nova verzija
                    </a>
                @endcan
            </div>

            @forelse($project->firmwareVersions as $version)
                <div class="border-t border-gray-200 py-3 flex items-center justify-between">
                    <div>
                        <div class="font-semibold">
                            Verzija {{ $version->version }}
                            @if($version->is_stable)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-800">
                                    Stabilna
                                </span>
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">
                            Objavljeno: {{ optional($version->released_at)->format('d.m.Y.') ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="space-x-2">
                        <a href="{{ route('firmware-versions.show', $version) }}"
                           class="text-indigo-600 text-sm hover:underline">
                            Detalji verzije
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nema verzija firmvera za ovaj projekat.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>

