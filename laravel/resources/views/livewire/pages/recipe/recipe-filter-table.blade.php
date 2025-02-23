<div class="container mx-auto px-4 py-40">
    <h1 class="text-3xl text-primary mb-6 text-center">
        Find the perfect recipe for your ingredients
    </h1>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Search by Recipe Name -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray mb-2">Search Recipe</label>
                <input wire:model.live="search" type="text" id="search"
                    class="w-full rounded-lg border-gray focus:border-primary focus:ring-primary"
                    placeholder="Search by recipe name...">
            </div>

            <div></div>
            <div></div>

            <!-- Servings Filter -->
            <div>
                <label for="servings" class="block text-sm font-medium text-gray mb-2">Servings</label>
                <select wire:model.live="servings" id="servings"
                    class="w-full rounded-lg border-gray focus:border-primary focus:ring-primary">
                    @foreach($servingsOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray mb-2">Category</label>
                <select wire:model.live="category" id="category"
                    class="w-full rounded-lg border-gray focus:border-primary focus:ring-primary">
                    @foreach($categoryOptions as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort" class="block text-sm font-medium text-gray mb-2">Sort By</label>
                <select wire:model.live="sort" id="sort"
                    class="w-full rounded-lg border-gray focus:border-primary focus:ring-primary">
                    @foreach($sortOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- No Results Message -->
    @if($noResults)
        <div class="text-center py-8">
            <p class="text-gray text-lg">No recipes found matching your criteria.</p>
        </div>
    @endif

    <!-- Recipe Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                                {{count($recipe->similar_matching_ingredients)}} Similar Matches
                            </span>
                        </div>
                        <div class="absolute top-12 right-2">
                            <span class="bg-lightgreen text-white px-3 py-1 rounded-full text-sm">
                                {{count($recipe->matching_ingredients)}} Matches
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
                                {{ Str::limit(strip_tags(implode(', ', array_filter($recipe->author, fn($name) => !empty($name)))), 30) }}
                            </span>
                        </div>

                        @if (is_array($recipe->categories))
                            <div class="flex flex-wrap items-center text-sm gap-2 pt-2">
                                @foreach (collect($recipe->categories)->take(5) as $category)
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
        {{ $recipes->links() }}
    </div>
</div>
