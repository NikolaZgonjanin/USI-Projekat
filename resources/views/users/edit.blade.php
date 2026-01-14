<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Izmena korisnika
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Ime i prezime</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Korisničko ime</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Nova lozinka (opcionalno)
                    </label>
                    <input type="password" name="password"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Potvrda nove lozinke</label>
                    <input type="password" name="password_confirmation"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Uloga</label>
                    <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="administrator" @selected(old('role', $user->role) === 'administrator')>
                            Administrator
                        </option>
                        <option value="engineer" @selected(old('role', $user->role) === 'engineer')>
                            Inženjer
                        </option>
                        <option value="client" @selected(old('role', $user->role) === 'client')>
                            Klijent
                        </option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('users.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:underline">
                        Otkaži
                    </a>

                    <x-primary-button>
                        Sačuvaj izmene
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

