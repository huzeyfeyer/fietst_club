@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('news.index') }}" class="text-blue-500 hover:underline">
                ← Terug naar overzicht
            </a>
        </div>

        <article class="bg-white rounded-lg shadow-lg p-6">
            <header class="mb-6">
                <h1 class="text-3xl font-bold mb-2">{{ $news->title }}</h1>
                <div class="text-gray-600">
                    <time datetime="{{ $news->created_at->format('Y-m-d') }}">
                        {{ $news->created_at->format('d/m/Y') }}
                    </time>
                    <span class="mx-2">•</span>
                    <span>{{ $news->user->name }}</span>
                </div>
            </header>

            <div class="prose max-w-none">
                {!! nl2br(e($news->content)) !!}
            </div>

            @auth
                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('news.edit', $news) }}" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Bewerken
                    </a>

                    <form action="{{ route('news.destroy', $news) }}" method="POST" 
                        onsubmit="return confirm('Weet je zeker dat je dit bericht wilt verwijderen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Verwijderen
                        </button>
                    </form>
                </div>
            @endauth
        </article>
    </div>
</div>
@endsection 