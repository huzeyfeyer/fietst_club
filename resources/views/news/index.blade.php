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
                    <div class="flex justify-between items-center mb-6">
                        @auth
                            <a href="{{ route('news.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                Nieuw bericht
                            </a>
                        @endauth
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-6">
                        @forelse($news as $item)
                            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow">
                                <h2 class="text-xl font-semibold mb-2">{{ $item->title }}</h2>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">
                                    {{ Str::limit($item->content, 200) }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('news.show', $item) }}" class="text-blue-500 hover:underline">
                                        Lees meer
                                    </a>
                                    <span class="text-gray-500 text-sm">
                                        {{ $item->created_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-600 dark:text-gray-400">Geen nieuwsberichten gevonden.</p>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $news->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 