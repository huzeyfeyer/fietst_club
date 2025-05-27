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

                    {{-- Sectie voor het indienen van een FAQ Suggestie --}}
                    @auth
                        <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8">
                            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">{{ __('Heeft u een andere vraag?') }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                {{ __('Stel uw vraag hieronder voor. Als het een veelgestelde vraag is, kunnen we deze toevoegen aan onze FAQ.') }}
                            </p>
                            <form action="{{ route('faq.suggest') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="suggestion_question" class="sr-only">{{ __('Uw vraag') }}</label>
                                    <textarea name="question" id="suggestion_question" rows="4" 
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm @error('question', 'faq_suggestion') border-red-500 dark:border-red-400 @enderror"
                                              placeholder="{{ __('Typ hier uw vraag...') }}" required minlength="10" maxlength="1000">{{ old('question') }}</textarea>
                                    @error('question', 'faq_suggestion') {{-- Specificeer de error bag als je er meerdere gebruikt --}}
                                        <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Vraag insturen') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-8 text-center">
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ __('Wilt u een vraag voorstellen? ') }} <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ __('Log in') }}</a> {{ __('om uw vraag in te dienen.') }}
                            </p>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </div>
</x-app-layout> 