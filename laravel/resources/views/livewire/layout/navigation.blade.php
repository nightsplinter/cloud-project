<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>
<nav x-data="{ open: false }" class="fixed w-full bg-white/80 backdrop-blur-md z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-20">
            <a href="/dashboard" title="Startseite">
                <x-application-logo/>
            </a>
            <div class="hidden md:flex space-x-6 justify-center">
                <x-nav-link
                    :href="route('dashboard')" title="Dashboard"
                    :active="request()->routeIs('dashboard')" wire:navigate>
                    My Pantry
                </x-nav-link>
                <x-nav-link
                    :href="route('recipe.finder')" title="Recipe Finder"
                    :active="request()->routeIs('recipe.finder')" wire:navigate>
                    Recipe Finder
                </x-nav-link>
                <x-nav-link
                    :href="route('recipe.add')" title="Add new Recipe"
                    :active="request()->routeIs('recipe.add')" wire:navigate>
                    Add new Recipe
                </x-nav-link>

                @if(Auth::user()->isAnalyst())
                    <x-nav-link
                        :href="route('analysis')"  title="Analysis"
                        :active="request()->routeIs('analysis')" wire:navigate>
                        Analysis
                    </x-nav-link>
                @endif

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

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

                        <!-- Authentication -->
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
</nav>
