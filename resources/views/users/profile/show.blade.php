<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profiel van') }} {{ $user->display_name ?? $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col items-center md:flex-row md:items-start">
                        <!-- Profielfoto -->
                        <div class="flex-shrink-0 mb-4 md:mb-0 md:mr-6">
                            @if ($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profielfoto van {{ $user->display_name ?? $user->name }}" class="rounded-full h-32 w-32 object-cover md:h-48 md:w-48">
                            @else
                                <div class="rounded-full h-32 w-32 md:h-48 md:w-48 bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 text-4xl md:text-6xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- Profiel Details -->
                        <div class="text-center md:text-left">
                            <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $user->display_name ?? $user->name }}</h3>
                            
                            @if ($user->about_me)
                                <p class="mt-4 text-gray-600 dark:text-gray-400 whitespace-pre-line">
                                    {{ $user->about_me }}
                                </p>
                            @else
                                <p class="mt-4 text-gray-500 dark:text-gray-500 italic">
                                    {{ __('Deze gebruiker heeft nog geen "Over mij"-tekst toegevoegd.') }}
                                </p>
                            @endif

                            {{-- Optioneel: Andere publieke informatie kan hier komen --}}
                            {{-- Bijvoorbeeld: Lid sinds, aantal posts, etc. --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 