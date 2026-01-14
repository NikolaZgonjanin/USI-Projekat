<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Prijave grešaka
            </h2>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-700">Prikaži zatvorene/odbijene</span>
                    <div class="relative inline-flex items-center">
                        <input type="checkbox" id="show-hidden-toggle"
                               class="sr-only peer"
                               {{ $showHidden ? 'checked' : '' }}
                               onchange="window.location.href = '{{ route('support-requests.index') }}?show_hidden=' + (this.checked ? '1' : '0')">
                        <div class="w-14 h-8 bg-gray-300 rounded-full shadow-inner transition-colors duration-200 ease-in-out peer-checked:bg-green-500"></div>
                        <div class="absolute left-0.5 top-0.5 w-7 h-7 bg-white rounded-full shadow-lg transform transition-transform duration-200 ease-in-out peer-checked:translate-x-6"></div>
                    </div>
                </label>
            </div>
        </div>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Naslov</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Projekat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Verzija</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prijavio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dodeljeno</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($supportRequests as $request)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $request->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->firmwareVersion->project->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->firmwareVersion->version }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'accepted' => 'bg-blue-100 text-blue-800',
                                        'denied' => 'bg-red-100 text-red-800',
                                        'closed' => 'bg-gray-100 text-gray-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->createdBy?->name ?? 'Nepoznato' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $request->assignedTo?->name ?? 'Nije dodeljeno' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('support-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Detalji
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                Nema prijava grešaka.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-3">
                {{ $supportRequests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
