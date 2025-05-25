<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $news->title }}
            </h2>
            <a href="{{ route('news.index') }}" class="text-blue-500 hover:underline">
                ← Terug naar overzicht
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Flash messages worden nu door de app layout afgehandeld --}}

                    <article>
                        <header class="mb-6">
                            {{-- Afbeelding van het nieuwsitem --}}
                            @if ($news->image_path)
                                <div class="mb-6">
                                    <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title }}" class="w-full h-auto max-h-96 object-cover rounded-lg shadow-md">
                                </div>
                            @endif

                            <div class="text-gray-600 dark:text-gray-400 text-sm mb-2">
                                <time datetime="{{ $news->created_at->format('Y-m-d') }}">
                                    Gepubliceerd op: {{ $news->created_at->format('d/m/Y') }}
                                </time>
                                <span class="mx-2">•</span>
                                <span class="flex items-center">
                                    Door:
                                    <a href="{{ route('users.profile.show', $news->user) }}" class="flex items-center ml-1 hover:underline">
                                        <img src="{{ $news->user && $news->user->profile_photo_path ? asset('storage/' . $news->user->profile_photo_path) : asset('images/placeholder-profile.png') }}" 
                                             alt="{{ $news->user->name }}" 
                                             class="w-6 h-6 rounded-full mr-1.5 object-cover">
                                        {{ $news->user->display_name ?? $news->user->name }}
                                    </a>
                                </span>
                            </div>

                            {{-- Toon categorieën als ze bestaan --}}
                            @if ($news->categories->isNotEmpty())
                                <div class="mb-4">
                                    <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">Categorieën:</span>
                                    @foreach ($news->categories as $index => $category)
                                        <a href="{{ route('news.index', ['category' => $category->slug]) }}" 
                                           class="ml-1 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">{{ $category->name }}</a>{{ !$loop->last ? ',' : '' }}
                                    @endforeach
                                </div>
                            @endif
                        </header>

                        <div class="prose dark:prose-invert max-w-none mt-4">
                            {!! nl2br(e($news->content)) !!}
                        </div>

                        @auth
                            <div class="mt-8 flex space-x-4">
                                @can('update', $news)
                                    <a href="{{ route('news.edit', $news) }}" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        Bewerken
                                    </a>
                                @endcan

                                @can('delete', $news)
                                    <form action="{{ route('news.destroy', $news) }}" method="POST" 
                                        onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                            Verwijderen
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endauth
                    </article>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 