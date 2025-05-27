<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Beheer FAQ Suggesties') }}
            </h2>
            {{-- Eventuele knop voor bulk acties of nieuwe suggestie (hoewel admin zelf geen suggestie aanmaakt) --}}
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Filterbalk --}}
            <div class="mb-6 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.faq-suggestions.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Filter op Status:') }}</label>
                        <select name="status" id="status" class="mt-1 block w-full sm:w-auto pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="all" @selected($currentStatus === 'all')>{{ __('Alle Statussen') }}</option>
                            <option value="pending" @selected($currentStatus === 'pending')>{{ __('In Afwachting') }}</option>
                            <option value="approved" @selected($currentStatus === 'approved')>{{ __('Goedgekeurd') }}</option>
                            <option value="rejected" @selected($currentStatus === 'rejected')>{{ __('Afgewezen') }}</option>
                        </select>
                    </div>
                    <button type="submit" class="self-end sm:self-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm transition ease-in-out duration-150">{{ __('Filter') }}</button>
                    <a href="{{ route('admin.faq-suggestions.index') }}" class="self-end sm:self-center px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 rounded-md shadow-sm border border-gray-300 dark:border-gray-600 transition ease-in-out duration-150">{{ __('Reset Filters') }}</a>
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($suggestions->isEmpty())
                        <p>{{ __('Er zijn momenteel geen FAQ suggesties die aan de criteria voldoen.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Vraag') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Ingediend door') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Datum') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Status') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Admin Notities') }}</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Acties') }}</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($suggestions as $suggestion)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($suggestion->question, 70) }}</div>
                                                @if(strlen($suggestion->question) > 70)
                                                    <button type="button" @click="alert('{{ addslashes(str_replace("\n", "\\n", $suggestion->question)) }}')" class="text-xs text-blue-500 hover:underline">{{ __('Volledige vraag') }}</button>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->user->name ?? __('Onbekend') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $suggestion->created_at->format('d-m-Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($suggestion->status === 'pending')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100">{{ __('In Afwachting') }}</span>
                                                @elseif ($suggestion->status === 'approved')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">{{ __('Goedgekeurd') }}</span>
                                                @elseif ($suggestion->status === 'rejected')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">{{ __('Afgewezen') }}</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200">{{ Str::title($suggestion->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ Str::limit($suggestion->admin_notes, 50) ?? '-' }}
                                                @if(strlen($suggestion->admin_notes) > 50)
                                                    <button type="button" @click="alert('{{ addslashes(str_replace("\n", "\\n", $suggestion->admin_notes)) }}')" class="text-xs text-blue-500 hover:underline">{{ __('Meer') }}</button>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                {{-- Modal of aparte pagina voor bewerken van admin_notes --}}
                                                {{-- <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:underline">Notities</a> --}}
                                                
                                                @if($suggestion->status === 'pending')
                                                    <form action="{{ route('admin.faq-suggestions.approve', $suggestion) }}" method="POST" class="inline-block">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 dark:text-green-200 dark:bg-green-700 dark:hover:bg-green-600 rounded shadow-sm">{{ __('Goedkeuren') }}</button>
                                                    </form>
                                                    <form action="{{ route('admin.faq-suggestions.reject', $suggestion) }}" method="POST" class="inline-block">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="px-2 py-1 text-xs font-medium text-orange-700 bg-orange-100 hover:bg-orange-200 dark:text-orange-200 dark:bg-orange-700 dark:hover:bg-orange-600 rounded shadow-sm">{{ __('Afwijzen') }}</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.faq-suggestions.destroy', $suggestion) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __("Weet u zeker dat u deze suggestie wilt verwijderen?") }}');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 dark:text-red-200 dark:bg-red-700 dark:hover:bg-red-600 rounded shadow-sm">{{ __('Verwijderen') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $suggestions->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- Alpine.js voor de @click alert, als het nog niet in app.js zit --}}
    {{-- <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> --}}
</x-app-layout> 