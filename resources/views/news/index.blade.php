@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Nieuwsberichten</h1>
        @auth
            <a href="{{ route('news.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
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
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">{{ $item->title }}</h2>
                <p class="text-gray-600 mb-4">
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
            <p class="text-gray-600">Geen nieuwsberichten gevonden.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $news->links() }}
    </div>
</div>
@endsection 