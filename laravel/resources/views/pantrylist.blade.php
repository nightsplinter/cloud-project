<x-app-layout>
    <div class="max-w-4xl self-center mx-auto p-5">
        <h1 class="text-2xl text-center pt-20 mb-6 font-light italic text-primary">
            What's in your pantry?
        </h1>
        <div class="p-6 rounded-lg bg-white">
            {{-- Filters --}}
            <div class="mb-6">
                <div class="flex space-x-4 justify-center items-center">

                    <!-- Search Bar -->
                    <div class="flex-1">
                        <label for="search" class="block text-xs font-medium text-gray-600">Search</label>
                        <input type="text" id="search" title="Search for item"
                            class="placeholder:italic px-4 py-1 rounded-lg border w-full focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"
                            placeholder="Search for items...">
                    </div>

                    {{-- Add Item Button --}}
                    <div class="flex-none items-baseline">
                        <label for="expirationFilter" class="block invisible text-xs font-medium text-gray-600">Add Item</label>
                        <a title="Add Item" href="{{ route('pantry.add') }}"
                            class="px-4 py-1 text-center rounded-lg w-full bg-primary text-white border-none cursor-pointer">
                            Add Item
                        </a>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="overflow-scroll pt-8 pb-5">
                <table class="w-2/3 mx-auto border-collapse bg-white table-auto">
                    <thead class="bg-lightgray">
                        <tr>
                            <th class="text-sm font-medium text-gray-600 p-2">Quantity</th>
                            <th class="text-sm font-medium text-gray-600 p-2">Name</th>
                            <th class="text-sm font-medium text-gray-600 p-2">Expiration date</th>
                            <th class="text-sm font-medium text-gray-600 p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($userPantryItems as $item)
                        <tr>
                            <td class="pl-10 text-sm text-gray-600">
                                {{$item['quantity'] . ' ' . $item['unit']}}
                            </td>
                            <td class="pl-10 text-sm text-gray-600">{{$item['name']}}</td>
                            <td class="pl-10 text-sm text-gray-600">{{$item['expiration_date']}}</td>
                            <td>
                                <div class="flex flex-row items-baseline justify-center">
                                    <a title="Edite" href="{{ route('pantry.edit', $item['id']) }}">
                                    <span class="cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                            class="w-4 h-4 m-2">
                                            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                            <path
                                                d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z" />
                                        </svg>
                                    </span>
                                </a>
                                @livewire('pantry.delete-item', ['itemId' => $item['id']])
                            </div>
                            </td>
                        </tr>
                        @endforeach
                        @if (empty($userPantryItems))
                            <tr>
                                <td colspan="4" class="text-center text-sm text-gray-600 p-3 text-primary">
                                    Your pantry is empty. Add some items!
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
