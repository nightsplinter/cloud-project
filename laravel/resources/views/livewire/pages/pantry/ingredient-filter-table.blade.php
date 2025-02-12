<div class="mx-auto bg-white rounded-2xl shadow-sm p-5 space-y-9">
    {{-- Filters --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <!-- Search Bar -->
        <div class="lg:col-span-4">
            <label for="search" class="block text-sm font-medium text-gray mb-2">Search Items</label>
            <div class="relative">
                <input type="text" id="search" wire:model.live="search"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border-gray focus:border-primary focus:ring-primary transition-colors duration-200"
                    placeholder="Search for items...">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Category Dropdown -->
        <div class="lg:col-span-3">
            <label for="category" class="block text-sm font-medium text-gray mb-2">Category</label>
            <select wire:model.live="category"
                class="w-full py-2.5 px-4 rounded-xl border-gray focus:border-primary focus:ring-primary transition-colors duration-200">
                @foreach($categoryOptions as $option)
                <option value="{{$option}}">{{ $option }}</option>
                @endforeach
            </select>
        </div>

        <!-- Expiration Date Dropdown -->
        <div class="lg:col-span-3">
            <label for="expirationDate" class="block text-sm font-medium text-gray mb-2">Expiration Date</label>
            <select wire:model.live="expirationDate"
                class="w-full py-2.5 px-4 rounded-xl border-gray focus:border-primary focus:ring-primary transition-colors duration-200">
                @foreach($expirationDateOptions as $key => $option)
                <option value="{{$key}}">{{ $option }}</option>
                @endforeach
            </select>
        </div>

        <!-- Add Item Button -->
        <div class="lg:col-span-2 flex items-end">
            <a href="{{ route('pantry.add') }}" title="Add Item"
                class="w-full bg-primary hover:bg-lightgreen text-white py-2.5 px-4 rounded-xl transition-colors duration-200 flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>Add Item</span>
            </a>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-lightgray">
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray">Quantity</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray">Name</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray">Expiration Date</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray">
                @foreach ($items as $item)
                <tr>
                    <td class="px-6 py-4 text-sm text-gray">
                        {{$item['quantity'] . ' ' . (isset($item['unit_index']) ? $item['ingredient']['unit'][$item['unit_index']] : '')}}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray">
                        {{$item['ingredient']['name']}}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray">
                        {{$item['expiration_date']}}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('pantry.edit', $item['id']) }}" title="Edit Item"
                                class="text-gray hover:text-primary transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <button wire:click="delete({{$item['id']}})" title="Delete Item"
                                    wire:confirm="Are you sure you want to delete this item?"
                                    class="text-gray hover:text-red transition-colors duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if (count($items) == 0)
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                            <svg class="w-12 h-12 text-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            <p class="text-gray text-sm">
                                @if ($noResults)
                                    No results found for your search
                                @else
                                    Your pantry is empty. Start by adding some items!
                                @endif
                            </p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
        <!-- Pagination -->
        <div class="mt-8 justify-center flex items-center">
            {{ $items->links() }}
        </div>
</div>
