<x-app-layout>
    <div class="py-40">
        <h1 class="text-xl font-semibold text-primary text-center">
            Analyst Dashboard
        </h1>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    <!-- Dash BI-Tool Card -->
                    <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                        <div class="p-6 h-full flex flex-col">
                            <div class="flex items-start justify-between flex-grow">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-12 h-12 fill-primary">
                                        <path d="M160 80c0-26.5 21.5-48 48-48l32 0c26.5 0 48 21.5 48 48l0 352c0 26.5-21.5 48-48 48l-32 0c-26.5 0-48-21.5-48-48l0-352zM0 272c0-26.5 21.5-48 48-48l32 0c26.5 0 48 21.5 48 48l0 160c0 26.5-21.5 48-48 48l-32 0c-26.5 0-48-21.5-48-48L0 272zM368 96l32 0c26.5 0 48 21.5 48 48l0 288c0 26.5-21.5 48-48 48l-32 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex flex-col h-full">
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-medium text-gray">Dash BI-Tool</h3>
                                        <p class="mt-1 text-sm text-gray">
                                            {{ __('Access business intelligence dashboard and analytics') }}
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <x-primary-button title="Open Dash Site" href="#" wire:navigate>
                                            Open Dash Site
                                        </x-primary-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laravel Plus Monitoring Card -->
                    <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                        <div class="p-6 h-full flex flex-col">
                            <div class="flex items-start justify-between flex-grow">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-12 h-12 fill-primary">
                                        <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex flex-col h-full">
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-medium text-gray">Laravel Plus Monitoring</h3>
                                        <p class="mt-1 text-sm text-gray">
                                            Monitor system performance and health metrics
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <x-primary-button title="View Monitoring" href="/pulse" wire:navigate>
                                            View Monitoring
                                        </x-primary-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ETL API Process Card -->
                    <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                        <div class="p-6 h-full flex flex-col">
                            <div class="flex items-start justify-between flex-grow">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-12 h-12 fill-primary">
                                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                        <path d="M304 48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zm0 416a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM48 304a48 48 0 1 0 0-96 48 48 0 1 0 0 96zm464-48a48 48 0 1 0 -96 0 48 48 0 1 0 96 0zM142.9 437A48 48 0 1 0 75 369.1 48 48 0 1 0 142.9 437zm0-294.2A48 48 0 1 0 75 75a48 48 0 1 0 67.9 67.9zM369.1 437A48 48 0 1 0 437 369.1 48 48 0 1 0 369.1 437z"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex flex-col h-full">
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-medium text-gray">ETL API Process</h3>
                                        <p class="mt-1 text-sm text-gray">
                                            Manually trigger the ETL API process. This is only possible <b>once per day</b> and only if the schedule has not already executed it.
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <form method="POST" action="{{ route('analysis.runCommand') }}" class="inline">
                                            @csrf
                                            <x-primary-button title="Run ETL API Request">
                                                Run ETL
                                            </x-primary-button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="py-12">
                    @if (session('status'))
                        <div class="bg-green border-l-4 border-green text-white p-4 mb-3">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red border-l-4 border-red text-white p-4 mb-3 text-center">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
