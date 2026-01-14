<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nova verzija firmvera
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('firmware-versions.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Projekat</label>
                    <select name="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @selected(old('project_id') == $project->id)>
                                {{ $project->name }} ({{ $project->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Verzija</label>
                    <input type="text" name="version" value="{{ old('version') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('version')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 flex items-center">
                    <input id="is_stable" type="checkbox" name="is_stable" value="1" class="rounded border-gray-300"
                           @checked(old('is_stable'))>
                    <label for="is_stable" class="ml-2 text-sm text-gray-700">Označi kao stabilnu verziju</label>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Changelog</label>
                    <textarea name="changelog" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('changelog') }}</textarea>
                    @error('changelog')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Putanja do fajla (simulacija)</label>
                    <input type="text" name="file_path" value="{{ old('file_path', 'firmware/dummy.bin') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <p class="mt-1 text-xs text-gray-500">Za potrebe projekta koristi se dummy fajl u storage/app/firmware/dummy.bin.</p>
                    @error('file_path')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('projects.index') }}" class="px-4 py-2 text-sm text-gray-700 hover:underline">
                        Otkaži
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700">
                        Sačuvaj verziju
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

{
  "cells": [],
  "metadata": {
    "language_info": {
      "name": "python"
    }
  },
  "nbformat": 4,
  "nbformat_minor": 2
}