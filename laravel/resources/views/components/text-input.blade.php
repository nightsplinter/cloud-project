@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray dark:border-gray dark:bg-gray dark:text-gray rounded-md shadow-sm focus:border-primary focus:ring-primary focus:ring-1']) }}>
