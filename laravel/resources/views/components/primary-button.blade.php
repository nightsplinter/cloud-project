<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex hover:bg-lightgreen items-center px-4 py-1 border border-transparent rounded-md text-center rounded-lg bg-primary text-white border-none cursor-pointer']) }}>
    {{ $slot }}
</button>
