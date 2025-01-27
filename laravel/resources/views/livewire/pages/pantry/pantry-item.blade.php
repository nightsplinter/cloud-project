<div class="max-w-4xl self-center mx-auto p-5">
    <h1 class="text-2xl text-center pt-20 mb-6 font-light italic text-primary">
        Add an item to your pantry list
    </h1>

    {{-- Error Message --}}
    @if (session()->has('message'))
        <div class="bg-red-100 border border-red text-red px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{session('message')}}</strong>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
              <svg class="fill-current h-6 w-6 text-red" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </span>
          </div>
    @endif

    <form class="p-6 rounded-lg bg-white" wire:submit="save" wire:keydown.enter.prevent>
        <div class="mb-6">
            <label for="name" class="text-sm text-gray-600">Ingredient</label>
            <input
                type="text"
                id="name"
                required
                min="2"
                max="255"
                wire:model.live="name"
                wire:keydown.arrow-down="incrementHighlight"
                wire:keydown.arrow-up="decrementHighlight"
                wire:keydown.enter="selectIngredient"
                wire:blur="resetIfInvalid"
                autocomplete="off"
                class="px-4 py-2 rounded-lg border w-full focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                placeholder="Search for an ingredient..."
            />
            <div>@error('name') <span class="text-red-500">{{ $message }}</span> @enderror</div>

            {{-- Dropdown --}}
            @if(!empty($name) && !$isEntrySelected)
                <div class="absolute z-10 bg-white border rounded-lg shadow-md mt-1">
                    @if(!empty($ingredients))
                        @foreach($ingredients as $index => $ingredient)
                            <div class="px-4 py-2 cursor-pointer {{ $highlightIndex === $index ? 'bg-lightgray' : '' }}"
                                title="{{$ingredient['name']}}">
                                <div class="flex items-center">
                                    {{-- Optional Image --}}
                                    @if(!empty($ingredient['picture']))
                                        <img src="{{ $ingredient['picture'] }}" alt="{{ $ingredient['name'] }}" class="w-6 h-6 rounded-full mr-2">
                                    @endif
                                    {{ $ingredient['name'] }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="px-4 py-2 text-gray-600">No results found</div>
                    @endif
                </div>
            @endif
        </div>
        <div class="mb-6 flex space-x-4">
            <div class="flex-1">
                <label for="quantity" class="text-sm text-gray-600">Quantity</label>
                <input type="number" min="1" wire:model="quantity" title="Quantity" placeholder="Quantity"
                    class="px-4 py-1 rounded-lg border w-full focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <div>@error('quantity') {{ $message }} @enderror</div>
            </div>
            <div class="flex-1">
                <label for="unit" class="text-sm text-gray-600">Unit</label>
                <select wire:model="unit" title="Unit"
                    class="px-4 py-1 rounded-lg border w-full focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                    <option value="" selected
                        class="text-gray-400">Select a unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit }}">{{ $unit }}</option>
                        @endforeach
                </select>
                <div>@error('unit') {{ $message }} @enderror</div>
            </div>
        </div>

        <div class="mb-6">
            <label for="expiration_date" class="text-sm text-gray-600">Expiration Date</label>
            <input type="date" wire:model="expiration_date" title="Expiration Date" placeholder="Expiration Date"
                class="px-4 py-1 rounded-lg border w-full focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <div>@error('expiration_date') {{ $message }} @enderror</div>
        </div>
        <button type="submit" title="{{ is_null($item) ? 'Add Item' : 'Update Item' }}"
            class="px-4 py-1 text-center rounded-lg w-full bg-primary text-white border-none cursor-pointer">
            @if (is_null($item))
                Add Item
            @else
                Update Item
            @endif
        </button>
    </form>
</div>
