<a wire:navigate {{ $attributes->merge(['class' => 'underline text-sm text-gray dark:text-gray hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-primary']) }}>
    {{ $slot }}
</a>
