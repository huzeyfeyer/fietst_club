<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Welkom bij Fiets Club Brussel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Laatste Nieuws') }}</h3>

                    @if(isset($latestNews) && $latestNews->count() > 0)
                        <ul class="space-y-4 mb-6">
                            @foreach($latestNews as $newsItem)
                                <li class="border-b border-gray-200 dark:border-gray-700 pb-2">
                                    @if(Route::has('news.show'))
                                        <a href="{{ route('news.show', $newsItem) }}" class="text-lg text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $newsItem->title }}
                                        </a>
                                    @else
                                        <span class="text-lg text-gray-800 dark:text-gray-200">{{ $newsItem->title }}</span>
                                    @endif
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Gepubliceerd op {{ $newsItem->created_at->format('d-m-Y') }}
                                        @if($newsItem->user) {{-- Controleer of user relatie geladen en aanwezig is --}}
                                            door {{ $newsItem->user->name }}
                                        @endif
                                    </p>
                                    {{-- Je kunt hier een kort uittreksel toevoegen als je dat wilt, bijv. Str::limit($newsItem->content, 150) --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>{{ __('Er is momenteel geen nieuws beschikbaar.') }}</p>
                    @endif
                    
                    @if(Route::has('news.index'))
                        <a href="{{ route('news.index') }}" class="text-blue-500 dark:text-blue-400 hover:underline">
                            {{ __('Bekijk al het nieuws') }} →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 