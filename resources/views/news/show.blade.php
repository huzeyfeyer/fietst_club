<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $news->title }}
            </h2>
            <a href="{{ route('news.index') }}" class="text-blue-500 dark:text-blue-400 hover:underline">
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
                                    <a href="{{ route('users.profile.show', $news->user) }}" class="flex items-center ml-1 text-gray-700 dark:text-indigo-400 hover:underline">
                                        @if($news->user && $news->user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $news->user->profile_photo_path) }}" 
                                                 alt="{{ $news->user->name }}" 
                                                 class="w-6 h-6 rounded-full mr-1.5 object-cover">
                                        @endif
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
                                        class="px-4 py-2 rounded font-semibold text-xs text-white uppercase tracking-widest bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                        {{ __('Bewerken') }}
                                    </a>
                                @endcan

                                @can('delete', $news)
                                    <form action="{{ route('news.destroy', $news) }}" method="POST" 
                                        onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 rounded font-semibold text-xs text-white uppercase tracking-widest bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            {{ __('Verwijderen') }}
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @endauth
                    </article>

                    {{-- Commentaar Sectie --}}
                    <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-100">Commentaren ({{ $news->comments->count() }})</h3>

                        {{-- Formulier om nieuwe commentaar te plaatsen (alleen voor ingelogde gebruikers) --}}
                        @auth
                            <form action="{{ route('comments.store', $news) }}" method="POST" class="mb-8">
                                @csrf
                                <div class="mb-4">
                                    <label for="comment_content" class="sr-only">{{ __('Uw commentaar') }}</label>
                                    <textarea name="content" id="comment_content" rows="4"
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('content') border-red-500 dark:border-red-400 @enderror"
                                              placeholder="{{ __('Schrijf uw commentaar hier...') }}" required minlength="3" maxlength="2000">{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Plaats Commentaar') }}
                                </button>
                            </form>
                        @else
                            <p class="mb-8 text-gray-600 dark:text-gray-400">
                                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">{{ __('Log in') }}</a> {{ __('om een commentaar te plaatsen.') }}
                            </p>
                        @endauth

                        {{-- Lijst van bestaande commentaren --}}
                        @if ($news->comments->isNotEmpty())
                            <div class="space-y-6">
                                @foreach ($news->comments as $comment)
                                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                        <div class="flex items-center mb-2">
                                            @if($comment->user && $comment->user->profile_photo_path)
                                                <img src="{{ asset('storage/' . $comment->user->profile_photo_path) }}" alt="{{ $comment->user->name }}" class="w-8 h-8 rounded-full mr-2 object-cover">
                                            @elseif($comment->user)
                                                <div class="w-8 h-8 rounded-full mr-2 bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm text-gray-700 dark:text-gray-200 font-semibold">
                                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="font-semibold text-gray-800 dark:text-gray-100">{{ $comment->user->name ?? __('Anonieme Gebruiker') }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $comment->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">{{ __('Er zijn nog geen commentaren voor dit nieuwsbericht.') }}</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout> 