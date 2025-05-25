<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Contactberichten Beheren') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Filters --}}
                    <div class="mb-4 flex flex-wrap items-center gap-4">
                        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-2">
                            <label for="filter_status" class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Toon:') }}</label>
                            <select name="filter_status" id="filter_status" onchange="this.form.submit()"
                                    class="block w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm">
                                <option value="all" {{ request('filter_status', 'all') == 'all' ? 'selected' : '' }}>{{ __('Alles (niet gearchiveerd)') }}</option>
                                <option value="unread" {{ request('filter_status') == 'unread' ? 'selected' : '' }}>{{ __('Ongelezen') }}</option>
                                <option value="read" {{ request('filter_status') == 'read' ? 'selected' : '' }}>{{ __('Gelezen') }}</option>
                            </select>
                            <input type="hidden" name="include_archived" value="{{ request('include_archived') }}">
                        </form>
                        <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="flex items-center">
                            <input type="hidden" name="filter_status" value="{{ request('filter_status', 'all') }}">
                            <label for="include_archived_checkbox" class="flex items-center text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
                                <input type="checkbox" name="include_archived" id="include_archived_checkbox" value="1" 
                                       {{ request('include_archived') ? 'checked' : '' }} onchange="this.form.submit()"
                                       class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                <span class="ms-2">{{ __('Gearchiveerde berichten tonen') }}</span>
                            </label>
                        </form>
                    </div>

                    @if ($messages->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Er zijn geen contactberichten die aan de criteria voldoen.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Afzender') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Onderwerp') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Ontvangen Op') }}</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Acties') }}</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($messages as $message)
                                        <tr class="{{ !$message->is_read && !$message->archived_at ? 'font-bold' : '' }} {{ $message->archived_at ? 'opacity-60' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @if ($message->archived_at)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-200">{{ __('Gearchiveerd') }}</span>
                                                @elseif ($message->is_read)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-700 text-green-800 dark:text-green-100">{{ __('Gelezen') }}</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-600 text-yellow-800 dark:text-yellow-100">{{ __('Ongelezen') }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $message->name }}<br>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $message->email }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-normal text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($message->subject, 50) ?: __('(Geen onderwerp)') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $message->created_at->isoFormat('LLL') }}</td> {{-- Nederlandse datum/tijd --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600 mr-3">{{ __('Bekijken') }}</a>
                                                {{-- Later: Knoppen voor archiveren/verwijderen --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if ($messages->hasPages())
                        <div class="mt-6">
                            {{ $messages->appends(request()->query())->links() }} {{-- Zorg dat filters behouden blijven bij paginatie --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 