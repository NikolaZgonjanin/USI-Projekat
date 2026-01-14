<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Projekti
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-300 px-4 py-2 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @can('create', App\Models\Project::class)
            <div class="flex justify-end mb-4">
                <a href="{{ route('projects.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                    Novi projekat
                </a>
            </div>
        @endcan

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Naziv</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Šifra</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Verzije firmvera</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Prijave grešaka</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($projects as $project)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $project->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $project->code }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $project->firmware_versions_count ?? $project->firmwareVersions()->count() }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                {{ $project->support_requests_count ?? 0 }}
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right space-x-2">
                                <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:underline">
                                    Detalji
                                </a>
                                @can('update', $project)
                                    <a href="{{ route('projects.edit', $project) }}" class="text-gray-700 hover:underline">
                                        Izmeni
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                Nema projekata.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-4 py-3">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

