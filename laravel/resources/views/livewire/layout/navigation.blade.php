<?php
use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void {
        $logout();
        $this->redirect('/', navigate: true);
    }
};
?>

<nav x-data="{ open: false }" class="fixed w-full bg-white/80 backdrop-blur-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="/dashboard" title="Startseite">
                <x-application-logo/>
            </a>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button @click="open = !open" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex space-x-6 justify-center">
                <x-nav-link :href="route('dashboard')" title="Dashboard" :active="request()->routeIs('dashboard')" wire:navigate>
                    My Pantry
                </x-nav-link>
                <x-nav-link :href="route('recipe.finder')" title="Recipe Finder" :active="request()->routeIs('recipe.finder')" wire:navigate>
                    Recipe Finder
                </x-nav-link>
                {{-- <x-nav-link :href="route('recipe.add')" title="Add new Recipe" :active="request()->routeIs('recipe.add')" wire:navigate>
                    Add new Recipe
                </x-nav-link> --}}
                @if(Auth::user()->isAnalyst())
                    <x-nav-link :href="route('analysis')" title="Analysis" :active="request()->routeIs('analysis')" wire:navigate>
                        Analysis
                    </x-nav-link>
                @endif

                <!-- User Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                                     x-text="name"
                                     x-on:profile-updated.window="name = $event.detail.name">
                                </div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" title="Profile" wire:navigate>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <button wire:click="logout" title="Logout" class="w-full text-start">
                                <x-dropdown-link>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="md:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                    My Pantry
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('recipe.finder')" :active="request()->routeIs('recipe.finder')" wire:navigate>
                    Recipe Finder
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('recipe.add')" :active="request()->routeIs('recipe.add')" wire:navigate>
                    Add new Recipe
                </x-responsive-nav-link>
                @if(Auth::user()->isAnalyst())
                    <x-responsive-nav-link :href="route('analysis')" :active="request()->routeIs('analysis')" wire:navigate>
                        Analysis
                    </x-responsive-nav-link>
                @endif
            </div>

            <!-- Mobile User Menu -->
            <div class="pt-4 pb-1 border-t border-gray">
                <div class="px-4">
                    <div class="font-medium text-base text-gray">
                        {{ Auth::user()->name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate>
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    <button wire:click="logout" class="w-full text-start">
                        <x-responsive-nav-link>
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
