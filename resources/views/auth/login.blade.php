<x-guest-layout>
    <x-slot name="header">
        {{ __('Welcome Back!') }}
    </x-slot>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-6">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email"
                class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-6">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                class="block mt-1 w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mb-6">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-800"
                    name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Forgot Password & Login Button -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Custom Footer -->
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __("Don't have an account?") }}
            <a href="{{ route('register') }}"
                class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                {{ __('Register here') }}
            </a>
        </p>
    </div>
</x-guest-layout>
