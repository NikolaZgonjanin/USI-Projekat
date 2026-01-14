<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prijava greške: {{ $supportRequest->title }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-100 border border-green-300 px-4 py-2 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-3">
            <p><span class="font-semibold">Projekat:</span> {{ $supportRequest->firmwareVersion->project->name }}</p>
            <p><span class="font-semibold">Verzija firmvera:</span> {{ $supportRequest->firmwareVersion->version }}</p>
            <p><span class="font-semibold">Status:</span> {{ ucfirst($supportRequest->status) }}</p>
            <p>
                <span class="font-semibold">Prijavio:</span>
                {{ $supportRequest->author->name }} ({{ $supportRequest->created_at?->format('d.m.Y. H:i') }})
            </p>
            <p>
                <span class="font-semibold">Dodeljen inženjer:</span>
                {{ $supportRequest->assignee?->name ?? 'Nije dodeljen' }}
            </p>
            <div class="mt-3">
                <h3 class="font-semibold mb-1">Opis problema</h3>
                <p class="whitespace-pre-line">{{ $supportRequest->request_text }}</p>
            </div>
            @if($supportRequest->steps_to_reproduce)
                <div class="mt-3">
                    <h3 class="font-semibold mb-1">Koraci za reprodukciju</h3>
                    <p class="whitespace-pre-line">{{ $supportRequest->steps_to_reproduce }}</p>
                </div>
            @endif
        </div>

        @if($engineers)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-3">Upravljanje prijavom (inženjer/admin)</h3>
                <form method="POST" action="{{ route('support-requests.update', $supportRequest) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            @foreach(['pending' => 'Na čekanju', 'accepted' => 'Prihvaćeno', 'denied' => 'Odbijeno', 'closed' => 'Zatvoreno'] as $value => $label)
                                <option value="{{ $value }}" @selected($supportRequest->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Dodeli inženjeru</label>
                        <select name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Niko</option>
                            @foreach($engineers as $engineer)
                                <option value="{{ $engineer->id }}" @selected($supportRequest->assigned_to === $engineer->id)>
                                    {{ $engineer->name }} ({{ $engineer->role }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                            Sačuvaj promene
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>

