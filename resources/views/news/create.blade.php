<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nieuw bericht maken
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Titel</label>
                            <input type="text" name="title" id="title" 
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                value="{{ old('title') }}" required>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Inhoud</label>
                            <textarea name="content" id="content" rows="6" 
                                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Afbeelding (optioneel)</label>
                            <input type="file" name="image" id="image"
                                class="w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600"
                                accept="image/png, image/jpeg, image/gif">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG of GIF (MAX. 2MB).</p>
                             @error('image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Categorieën (optioneel)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 mt-1">
                                @forelse ($categories as $category)
                                    <label for="category_{{ $category->id }}" class="flex items-center p-2 border border-gray-300 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                        <input type="checkbox" name="categories[]" id="category_{{ $category->id }}" value="{{ $category->id }}"
                                               {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}
                                               class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                        <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">{{ $category->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">Er zijn nog geen categorieën aangemaakt. <a href="{{ route('admin.news-categories.create') }}" class="underline hover:text-indigo-600 dark:hover:text-indigo-400">Maak er eerst een aan.</a></p>
                                @endforelse
                            </div>
                             @error('categories') {{-- Algemene error voor categories array --}}
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                             @error('categories.*') {{-- Error per individuele category in de array --}}
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-between items-center mt-6">
                            <a href="{{ route('news.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline">
                                Annuleren
                            </a>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Opslaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 