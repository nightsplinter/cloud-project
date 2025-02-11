@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray rounded-md shadow-sm focus:border-primary focus:ring-primary focus:ring-1']) }}>
