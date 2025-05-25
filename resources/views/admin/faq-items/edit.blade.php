<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('FAQ Item Bewerken') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.faq-items.update', $faqItem) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Question -->
                        <div class="mb-4">
                            <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Vraag') }}</label>
                            <textarea name="question" id="question" rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      required>{{ old('question', $faqItem->question) }}</textarea>
                            @error('question')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Answer -->
                        <div class="mb-4">
                            <label for="answer" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Antwoord') }}</label>
                            <textarea name="answer" id="answer" rows="5"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      required>{{ old('answer', $faqItem->answer) }}</textarea>
                            @error('answer')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- FAQ Category -->
                        <div class="mb-4">
                            <label for="faq_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('FAQ Categorie') }}</label>
                            <select name="faq_category_id" id="faq_category_id"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">{{ __('Geen categorie') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('faq_category_id', $faqItem->faq_category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faq_category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.faq-items.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline mr-4">
                                {{ __('Annuleren') }}
                            </a>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('FAQ Item Bijwerken') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 