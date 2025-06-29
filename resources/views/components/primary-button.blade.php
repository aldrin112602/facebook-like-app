<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => '
        inline-flex items-center justify-center
        px-4 py-2
        bg-gradient-to-r from-purple-500 to-blue-500
        dark:from-purple-600 dark:to-blue-600
        text-white dark:text-white
        rounded-md text-sm font-semibold uppercase tracking-wider
        hover:from-purple-600 hover:to-blue-600
        dark:hover:from-purple-700 dark:hover:to-blue-700
        focus:outline-none focus:ring-2 focus:ring-offset-2
        focus:ring-purple-400 dark:focus:ring-purple-600
        focus:ring-offset-white dark:focus:ring-offset-gray-800
        transition duration-150 ease-in-out
    '
]) }}>
    {{ $slot }}
</button>

