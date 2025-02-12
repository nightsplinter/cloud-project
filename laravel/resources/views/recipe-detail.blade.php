<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="relative h-96 rounded-xl overflow-hidden mb-8">

            @if($recipe->picture)
                <img src="{{$recipe->picture}}" alt="{{ $recipe->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-lightgray flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            @endif

            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-6">
                <h1 class="text-4xl font-bold text-primary mb-2">{{$recipe->name}}</h1>
                <div class="flex items-center text-white/90">
                    <span class="mr-4">Created by {{ strip_tags(implode(', ', array_filter($recipe->author, fn($name) => !empty($name)))) }}</span>
                </div>
                <div class="flex items-center text-white/90">
                    <span>{{$recipe->servings}} Servings</span>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left Column --}}
            <div class="lg:col-span-2">
                {{-- Description --}}
                <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Description</h2>
                    <p class="text-gray">{!! $recipe->description !!}</p>
                </div>

                {{-- Steps --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-6">Zubereitung</h2>
                    <div class="space-y-6">
                        @foreach(eval("return $recipe->steps;") as $key => $step)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white font-semibold">
                                {{-- {{ $step }} --}}
                            </div>
                            <div>
                                <p class="text-gray">{{ $step }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="space-y-8">

                {{-- Ingredients --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Ingredients</h2>
                        {{var_dump()}}
                    </div>
                    <ul class="space-y-3">
                        {{-- @foreach($recipe->raw_str as $ingredient) --}}
                        <li class="flex items-center justify-between p-0.5 rounded">
                            <span class="flex items-center gap-2">
                                {{-- @if($ingredient['available'])
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                @endif --}}
                                {{-- {{ $ingredient }} --}}
                            </span>
                            {{-- <span class="text-gray">{{ $ingredient['amount'] }}</span> --}}
                        </li>
                        {{-- @endforeach --}}
                    </ul>
                </div>

                {{-- Categories --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-4">Categories</h2>
                    <div class="flex flex-wrap gap-2">
                        @if (is_array($recipe->categories))
                            <div class="flex flex-wrap items-center text-sm gap-2 pt-2">
                                @foreach ($recipe->categories as $category)
                                    <span class="px-3 py-1 text-gray rounded-full text-sm">
                                        {{ $category }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <span class="text-gray">No categories</span>
                        @endif
                    </div>
                </div>

                {{-- Source --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-2xl font-semibold mb-4">Source</h2>
                    @if (filter_var($recipe->source, FILTER_VALIDATE_URL))
                        <a href="{{ $recipe->source }}" target="_blank">Source Link</a>
                    @else
                        <span>{{ $recipe->source }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
