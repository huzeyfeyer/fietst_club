<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Veelgestelde Vragen (FAQ)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($faqCategories->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Er zijn momenteel geen veelgestelde vragen beschikbaar.') }}</p>
                    @else
                        <div class="space-y-8">
                            @foreach ($faqCategories as $category)
                                @if ($category->faqItems->isNotEmpty())
                                    <section>
                                        <h3 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ $category->name }}</h3>
                                        <div class="space-y-4">
                                            @foreach ($category->faqItems as $faqItem)
                                                <details class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow">
                                                    <summary class="font-medium text-lg text-gray-900 dark:text-gray-100 cursor-pointer hover:text-blue-600 dark:hover:text-blue-400">
                                                        {{ $faqItem->question }}
                                                    </summary>
                                                    <div class="mt-2 text-gray-700 dark:text-gray-300 prose dark:prose-invert max-w-none">
                                                        {!! nl2br(e($faqItem->answer)) !!} {{-- Gebruik nl2br voor line breaks, en e() voor escaping --}}
                                                    </div>
                                                </details>
                                            @endforeach
                                        </div>
                                    </section>
                                @endif
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout> 