<x-guest-layout>
    <x-slot name="header">
        {{ __('Confirm Your Password') }}
    </x-slot>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400"
                type="password"
                name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Button -->
        <div class="flex justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
