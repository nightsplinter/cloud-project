<x-app-layout>
    <div class="max-w-4xl self-center mx-auto p-5">
        <h1 class="text-2xl text-center pt-20 mb-6 font-light italic text-primary">
            What's in your pantry?
        </h1>
        @livewire('ingredient-filter-table', ['userPantryItems' => $userPantryItems])
    </div>
</x-app-layout>
