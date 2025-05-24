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
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <article>
                        <header class="mb-6">
                            <div class="text-gray-600 dark:text-gray-400">
                                <time datetime="{{ $news->created_at->format('Y-m-d') }}">
                                    {{ $news->created_at->format('d/m/Y') }}
                                </time>
                                <span class="mx-2">•</span>
                                <span>{{ $news->user->name }}</span>
                            </div>
                        </header>

                        <div class="prose dark:prose-invert max-w-none">
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