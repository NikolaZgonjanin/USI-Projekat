<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Prijavi problem
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <form method="POST" action="{{ route('support-requests.store') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Verzija firmvera</label>
                    <select name="firmware_version_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                        @foreach($firmwareVersions as $version)
                            <option value="{{ $version->id }}" @selected(old('firmware_version_id', $firmwareVersionId) == $version->id)>
                                {{ $version->project->name }} – v{{ $version->version }}
                            </option>
                        @endforeach
                    </select>
                    @error('firmware_version_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Naslov prijave</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Opis problema</label>
                    <textarea name="request_text" rows="5" required
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('request_text') }}</textarea>
                    @error('request_text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Koraci za reprodukciju (opciono)</label>
                    <textarea name="steps_to_reproduce" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('steps_to_reproduce') }}</textarea>
                    @error('steps_to_reproduce')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ url()->previous() }}" class="px-4 py-2 text-sm text-gray-700 hover:underline">
                        Otkaži
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md hover:bg-red-700">
                        Pošalji prijavu
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