<x-guest-layout>
    <x-slot name="header">
        {{ __('Forgot Your Password?') }}
    </x-slot>

    <!-- Description Text -->
    <div class="mb-6 text-sm text-gray-600 dark:text-gray-400">
        {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Form -->
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-800 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-500 dark:focus:border-indigo-500"
                            type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>

    
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __("Remember your password?") }}
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                {{ __('Log in here') }}
            </a>
        </p>
    </div>
</x-guest-layout>
