<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nieuwsberichten
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row justify-between md:items-center mb-6">
                        <h1 class="text-2xl font-semibold text-gray-800 dark:text-sky-300 mb-4 md:mb-0">
                            @if ($activeCategory)
                                Nieuws in categorie: <span class="text-green-600 dark:text-sky-400">{{ $activeCategory->name }}</span>
                            @else
                                Laatste Nieuws
                            @endif
                        </h1>
                        @can('create', App\Models\News::class)
                            <a href="{{ route('news.create') }}" class="bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white px-4 py-2 rounded self-start md:self-auto">
                                Nieuw bericht
                            </a>
                        @endcan
                    </div>

                    {{-- Categorie Filter --}}
                    @if ($categories->isNotEmpty())
                        <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-3">Filter op categorie:</h3>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('news.index') }}"
                                   class="px-3 py-1 text-sm rounded-full transition-colors 
                                          {{ !$activeCategory ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 hover:bg-indigo-200 dark:hover:bg-indigo-500' }}">
                                    Alle Categorieën
                                </a>
                                @foreach ($categories as $category)
                                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                                       class="px-3 py-1 text-sm rounded-full transition-colors 
                                              {{ $activeCategory && $activeCategory->id == $category->id ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 hover:bg-indigo-200 dark:hover:bg-indigo-500' }}">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="space-y-8">
                        @forelse($news as $item)
                            <article class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-lg dark:border dark:border-gray-600 flex flex-col md:flex-row md:space-x-6">
                                @if ($item->image_path)
                                    <div class="md:w-1/3 lg:w-1/4 mb-4 md:mb-0 flex-shrink-0">
                                        <a href="{{ route('news.show', $item) }}">
                                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="w-full h-48 object-cover rounded-md hover:opacity-90 transition-opacity">
                                        </a>
                                    </div>
                                @endif

                                <div class="flex-grow {{ $item->image_path ? 'md:w-2/3 lg:w-3/4' : 'w-full' }}">
                                    <header class="mb-3">
                                        <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 hover:text-sky-600 dark:hover:text-sky-400 transition-colors">
                                            <a href="{{ route('news.show', $item) }}">{{ $item->title }}</a>
                                        </h2>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                            <img src="{{ $item->user && $item->user->profile_photo_path ? asset('storage/' . $item->user->profile_photo_path) : asset('images/placeholder-profile.png') }}" 
                                                 alt="{{ $item->user->name ?? '' }}" 
                                                 class="w-6 h-6 rounded-full mr-2 object-cover">
                                            Gepubliceerd op {{ $item->created_at->format('d/m/Y') }} door {{ $item->user->display_name ?? $item->user->name ?? 'Onbekende auteur' }}
                                        </p>
                                        @if ($item->categories->isNotEmpty())
                                            <div class="mt-2 text-xs">
                                                <span class="font-semibold text-gray-600 dark:text-gray-300">Categorieën:</span>
                                                @foreach ($item->categories as $category)
                                                    <a href="{{ route('news.index', ['category' => $category->slug]) }}"
                                                       class="ml-1 px-2 py-0.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-colors">{{ $category->name }}</a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </header>
                                    <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 mb-4">
                                        {!! Str::limit(nl2br(e($item->content)), $item->image_path ? 200 : 300) !!}
                                    </div>
                                    <a href="{{ route('news.show', $item) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:underline dark:hover:underline font-semibold">
                                        Lees meer →
                                    </a>
                                </div>
                            </article>
                        @empty
                            <p class="text-gray-600 dark:text-gray-400 py-8 text-center">
                                @if ($activeCategory)
                                    Geen nieuwsberichten gevonden in de categorie "{{ $activeCategory->name }}".
                                @else
                                    Geen nieuwsberichten gevonden.
                                @endif
                            </p>
                        @endforelse
                    </div>

                    @if($news->hasPages())
                        <div class="mt-8">
                            {{ $news->links() }} {{-- Paginatie behoudt nu de 'category' query string dankzij withQueryString() in controller --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 