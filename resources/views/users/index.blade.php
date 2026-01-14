<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lista korisnika
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-300 px-4 py-2 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                Kreiraj korisnika
            </a>
        </div>

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ime</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Korisničko ime</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Uloga</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Akcije</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $user->username }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-4 py-2 text-sm text-gray-900">
                                @switch($user->role)
                                    @case('administrator') Administrator @break
                                    @case('engineer') Inženjer @break
                                    @case('client') Klijent @break
                                @endswitch
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-900 text-right space-x-2">
                                <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:underline">Izmeni</a>

                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Da li ste sigurni da želite da obrišete korisnika?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Obriši</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500">
                                Nema korisnika.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-4 py-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

