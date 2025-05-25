<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profielinformatie') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Werk de profielinformatie en het e-mailadres van uw account bij.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Naam (Login)')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="display_name" :value="__('Weergavenaam (Publiek)')" />
            <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" :value="old('display_name', $user->display_name)" autocomplete="nickname" />
            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('E-mailadres')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Uw e-mailadres is niet geverifieerd.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Klik hier om de verificatie-e-mail opnieuw te verzenden.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('Een nieuwe verificatielink is naar uw e-mailadres verzonden.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="birthday" :value="__('Geboortedatum')" />
            <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full" :value="old('birthday', $user->birthday ? $user->birthday->format('Y-m-d') : '')" />
            <x-input-error class="mt-2" :messages="$errors->get('birthday')" />
        </div>

        <div>
            <x-input-label for="about_me" :value="__('Over mij')" />
            <textarea id="about_me" name="about_me" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('about_me', $user->about_me) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('about_me')" />
        </div>

        <div>
            <x-input-label for="profile_photo" :value="__('Profielfoto')" />
            @if ($user->profile_photo_path)
                <div class="mt-2 mb-2">
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profielfoto" class="rounded-full h-20 w-20 object-cover">
                    <label for="remove_profile_photo" class="inline-flex items-center mt-2">
                        <input type="checkbox" id="remove_profile_photo" name="remove_profile_photo" value="1" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Huidige profielfoto verwijderen') }}</span>
                    </label>
                </div>
            @endif
            <input id="profile_photo" name="profile_photo" type="file" class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:border-indigo-500 dark:focus:border-indigo-600" accept="image/png, image/jpeg, image/gif">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" id="file_input_help">PNG, JPG of GIF (MAX. 2MB).</p>
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Opslaan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Opgeslagen.') }}</p>
            @endif
        </div>
    </form>
</section>
