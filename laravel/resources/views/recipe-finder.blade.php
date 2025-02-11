<x-app-layout>
    <div class="container mx-auto px-4 py-40">
        <h1 class="text-3xl text-primary mb-6 text-center">
            Find the pefect recipe for your ingredients
        </h1>
        <div class="grid grid-cols-3 gap-6">
            @foreach($recipes as $recipe)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-300 hover:transform hover:scale-105">
                    <a href="{{ route('recipe.show', base64_encode($recipe->_id)) }}" wire:navigate
                        class="block cursor-pointer" title="{{ $recipe->name }}">
                        <div class="relative h-48">
                            @if($recipe->picture)
                                <img src="{{ $recipe->picture }}"
                                    alt="{{ $recipe->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-lightgray flex items-center justify-center">
                                    <svg class="w-12 h-12 text-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm">
                                    {{$recipe->matching_ingredients}} Matches
                                </span>
                            </div>
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-gray mb-2">
                                {{$recipe->name}}
                            </h3>
                            <div class="flex items-center gap-4 text-gray text-sm mb-3">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    {{ $recipe->servings }}
                                    Servings
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    {{ $recipe->total_ingredients }}
                                    Ingredients
                                </div>
                            </div>

                            <div class="flex items-center text-sm text-gray">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                from&nbsp;
                                <span class="overflow-hidden truncate">
                                    <span class="overflow-hidden truncate">
                                        {{ Str::limit(strip_tags(implode(', ', array_filter($recipe->author, fn($name) => !empty($name)))), 30) }}
                                    </span>
                                </span>
                            </div>

                            @if (is_array($recipe->categories))
                                <div class="flex flex-wrap items-center text-sm gap-2 pt-2">
                                    @foreach ($recipe->categories as $category)
                                        <span class="bg-gray text-white px-2 py-1 rounded-full">
                                            {{ $category }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 justify-center flex items-center">
            {{$recipes->links('vendor.pagination.tailwind')}}
        </div>
    </div>

</x-app-layout>
