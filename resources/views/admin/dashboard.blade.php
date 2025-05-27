{{-- resources/views/admin/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Statistieken --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                {{-- Totaal Gebruikers --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Totaal Gebruikers') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalUsers }}</p>
                    </div>
                </div>

                {{-- Totaal Nieuwsberichten --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Nieuwsberichten') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalNewsItems }}</p>
                    </div>
                </div>

                {{-- Ongelezen Contactberichten --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Ongelezen Berichten') }}</h3>
                        <p class="mt-1 text-3xl font-semibold {{ $unreadContactMessages > 0 ? 'text-red-500' : 'text-indigo-600' }} dark:text-indigo-400">
                            {{ $unreadContactMessages }}
                        </p>
                        @if ($unreadContactMessages > 0)
                            <a href="{{ route('admin.contact-messages.index', ['filter_status' => 'unread']) }}" class="text-sm text-blue-600 dark:text-indigo-400 hover:underline">{{ __('Bekijken') }}</a>
                        @endif
                    </div>
                </div>

                {{-- Totaal FAQ Items --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('FAQ Items') }}</h3>
                        <p class="mt-1 text-3xl font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalFaqItems }}</p>
                    </div>
                </div>
            </div>

            {{-- Snelle Links --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('Beheer Secties') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="block p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Gebruikersbeheer') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Beheer gebruikers en rollen') }}</p>
                        </a>
                        <a href="{{ route('news.create') }}" class="block p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Nieuwsbericht Toevoegen') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Maak een nieuw nieuwsbericht aan') }}</p>
                        </a>
                        <a href="{{ route('admin.news-categories.index') }}" class="block p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Nieuwscategorieën') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Beheer nieuwscategorieën') }}</p>
                        </a>
                        <a href="{{ route('admin.faq-categories.index') }}" class="block p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('FAQ Categorieën') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Beheer FAQ categorieën') }}</p>
                        </a>
                        <a href="{{ route('admin.faq-items.index') }}" class="block p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('FAQ Items') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Beheer FAQ items') }}</p>
                        </a>
                        <a href="{{ route('admin.contact-messages.index') }}" class="block p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ __('Contactberichten') }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Bekijk en beheer contactberichten') }}</p>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>