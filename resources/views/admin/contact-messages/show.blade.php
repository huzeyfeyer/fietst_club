<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Contactbericht Bekijken') }}
            </h2>
            <a href="{{ route('admin.contact-messages.index', session('contact_messages_index_filters', [])) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                &larr; {{ __('Terug naar overzicht') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Onderwerp:') }} {{ $contactMessage->subject ?: __('(Geen onderwerp)') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><strong class="text-gray-600 dark:text-gray-400">{{ __('Van:') }}</strong> {{ $contactMessage->name }}</p>
                            <p><strong class="text-gray-600 dark:text-gray-400">{{ __('E-mail:') }}</strong> <a href="mailto:{{ $contactMessage->email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $contactMessage->email }}</a></p>
                        </div>
                        <div>
                            <p><strong class="text-gray-600 dark:text-gray-400">{{ __('Ontvangen:') }}</strong> {{ $contactMessage->created_at->isoFormat('LLLL') }} ({{ $contactMessage->created_at->diffForHumans() }})</p>
                            <p><strong class="text-gray-600 dark:text-gray-400">{{ __('Status:') }}</strong> 
                                @if ($contactMessage->archived_at)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">{{ __('Gearchiveerd') }} op {{ $contactMessage->archived_at->isoFormat('L') }}</span>
                                @elseif ($contactMessage->is_read)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-100">{{ __('Gelezen') }}</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-600 text-yellow-800 dark:text-yellow-100">{{ __('Ongelezen') }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="font-medium text-gray-800 dark:text-gray-200 mb-2">{{ __('Bericht:') }}</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($contactMessage->message)) !!}
                        </div>
                    </div>

                    {{-- Actie Knoppen --}}
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6 flex flex-wrap gap-3">
                        {{-- Markeer als ongelezen (als het gelezen is en niet gearchiveerd) --}}
                        @if ($contactMessage->is_read && !$contactMessage->archived_at)
                            <form action="{{ route('admin.contact-messages.markAsUnread', $contactMessage) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 hover:bg-yellow-200 dark:text-yellow-200 dark:bg-yellow-700 dark:hover:bg-yellow-600 rounded-md shadow-sm">{{ __('Markeer als ongelezen') }}</button>
                            </form>
                        @endif

                        {{-- Archiveer / De-archiveer --}}
                        <form action="{{ $contactMessage->archived_at ? route('admin.contact-messages.unarchive', $contactMessage) : route('admin.contact-messages.archive', $contactMessage) }}" method="POST" class="inline-block">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 dark:text-gray-200 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-md shadow-sm">
                                {{ $contactMessage->archived_at ? __('De-archiveer') : __('Archiveer') }}
                            </button>
                        </form>

                        {{-- Verwijder --}}
                        <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __("Weet je zeker dat je dit bericht permanent wilt verwijderen?") }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-red-700 bg-red-100 hover:bg-red-200 dark:text-red-200 dark:bg-red-700 dark:hover:bg-red-600 rounded-md shadow-sm">{{ __('Permanent Verwijderen') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout> 