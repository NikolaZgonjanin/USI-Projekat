<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Verzija firmvera {{ $firmwareVersion->version }} – {{ $firmwareVersion->project->name }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-300 px-4 py-2 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-2">
            <p><span class="font-semibold">Projekat:</span> {{ $firmwareVersion->project->name }}</p>
            <p>
                <span class="font-semibold">Status:</span>
                @if($firmwareVersion->is_stable)
                    Stabilna verzija
                @else
                    Nestabilna / razvojna verzija
                @endif
            </p>
            <p>
                <span class="font-semibold">Datum objave:</span>
                {{ optional($firmwareVersion->released_at)->format('d.m.Y.') ?? 'N/A' }}
            </p>
            <p class="mt-2">
                <span class="font-semibold">Changelog:</span><br>
                {{ $firmwareVersion->changelog ?: 'Nema unetog changelog zapisa.' }}
            </p>

            <div class="mt-4 flex space-x-3">
                @if($firmwareVersion->file_path)
                    <a href="{{ route('firmware.download', $firmwareVersion) }}"
                       class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                        Preuzmi firmver
                    </a>
                @endif

                <a href="{{ route('support-requests.create', ['firmware_version_id' => $firmwareVersion->id]) }}"
                   class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700">
                    Prijavi problem
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Dodatna dokumentacija</h3>
            @forelse($firmwareVersion->documentations as $doc)
                <div class="border-t border-gray-200 py-2 flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ $doc->title }}</div>
                        <div class="text-sm text-gray-600">{{ $doc->description }}</div>
                    </div>
                    @if($doc->file_path)
                        <a href="{{ asset('storage/' . $doc->file_path) }}" class="text-indigo-600 text-sm hover:underline">
                            Preuzmi
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-sm text-gray-500">Nema dodatne dokumentacije.</p>
            @endforelse
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Prijave grešaka</h3>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-700">Prikaži zatvorene/odbijene</span>
                    <div class="relative">
                        <input type="checkbox" id="show-hidden-toggle" 
                               class="sr-only peer"
                               {{ $showHidden ?? false ? 'checked' : '' }}
                               onchange="window.location.href = '{{ route('firmware-versions.show', $firmwareVersion) }}?show_hidden=' + (this.checked ? '1' : '0')">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </div>
                </label>
            </div>
            @forelse($supportRequests ?? $firmwareVersion->supportRequests as $request)
                <div class="border-t border-gray-200 py-2 flex items-center justify-between">
                    <div>
                        <div class="font-semibold">{{ $request->title }}</div>
                        <div class="text-sm text-gray-600">
                            Status: {{ ucfirst($request->status) }} · Prijavio: {{ $request->createdBy?->name ?? 'Nepoznato' }}
                        </div>
                    </div>
                    <a href="{{ route('support-requests.show', $request) }}"
                       class="text-indigo-600 text-sm hover:underline">
                        Detalji prijave
                    </a>
                </div>
            @empty
                <p class="text-sm text-gray-500">Nema prijavljenih problema za ovu verziju.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>

