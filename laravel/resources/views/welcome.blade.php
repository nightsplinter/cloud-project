<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-background font-inria">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Recipe Finder - Turn your ingredients into delicious meals and reduce food waste">
    <title>Recipe Finder</title>
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <nav class="fixed w-full bg-white/80 backdrop-blur-md z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <a href="/" title="Mainpage">
                    <x-application-logo/>
                </a>
                <div class="hidden md:flex space-x-8">
                    <a href="#how-it-works" title="How it works" class="text-gray hover:text-primary hover:underline">How it Works</a>
                    <a href="#features" title="Features" class="text-gray hover:text-primary hover:underline">Features</a>
                    <a href="#testimonials" title="Testimonials" class="text-gray hover:text-primary hover:underline">Testimonials</a>
                </div>
                @auth
                    <a href="{{ url('/dashboard') }}" title="Dashboard"
                        class="bg-primary text-white px-6 py-2 rounded-full hover:bg-lightgreen transition">
                    Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" title="Register"
                        class="bg-primary text-white px-6 py-2 rounded-full hover:bg-lightgreen transition">Get Started</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 bg-gradient-to-b from-white to-background">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-5xl font-bold mb-6 bg-gradient-to-r from-primary bg-clip-text text-transparent">
                    Turn Your Ingredients into Delicious Meals
                </h1>
                <p class="text-xl text-gray-600 mb-8">Stop food waste. Make the most of what you already have.</p>
                <a href="{{ route('register') }}" title="Register" class="inline-flex items-center bg-primary text-white px-8 py-3 rounded-full hover:bg-lightgreen transition transform hover:scale-105">
                    Start cooking sustainably
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8">
                <div class="group p-8 rounded-2xl border border-gray hover:border-primary transition-all hover:shadow-lg hover:shadow-primary">
                    <div class="w-14 h-14 bg-primary rounded-xl flex items-center justify-center mb-6 transition-colors">
                        <svg class="w-9 h-9 fill-white/80" width="100%" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d="M240 144A96 96 0 1 0 48 144a96 96 0 1 0 192 0zm44.4 32C269.9 240.1 212.5 288 144 288C64.5 288 0 223.5 0 144S64.5 0 144 0c68.5 0 125.9 47.9 140.4 112l71.8 0c8.8-9.8 21.6-16 35.8-16l104 0c26.5 0 48 21.5 48 48s-21.5 48-48 48l-104 0c-14.2 0-27-6.2-35.8-16l-71.8 0zM144 80a64 64 0 1 1 0 128 64 64 0 1 1 0-128zM400 240c13.3 0 24 10.7 24 24l0 8 96 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-240 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l96 0 0-8c0-13.3 10.7-24 24-24zM288 464l0-112 224 0 0 112c0 26.5-21.5 48-48 48l-128 0c-26.5 0-48-21.5-48-48zM48 320l80 0 16 0 32 0c26.5 0 48 21.5 48 48s-21.5 48-48 48l-16 0c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32-14.3-32-32l0-80c0-8.8 7.2-16 16-16zm128 64c8.8 0 16-7.2 16-16s-7.2-16-16-16l-16 0 0 32 16 0zM24 464l176 0c13.3 0 24 10.7 24 24s-10.7 24-24 24L24 512c-13.3 0-24-10.7-24-24s10.7-24 24-24z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Smart Kitchen Management</h3>
                    <p class="text-gray">Track your pantry and never let ingredients go to waste again.</p>
                </div>
                <div class="group p-8 rounded-2xl border border-gray hover:border-primary transition-all hover:shadow-lg hover:shadow-primary">
                    <div class="w-14 h-14 bg-primary rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
                        <svg class="w-9 h-9 fill-white/80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M249.6 471.5c10.8 3.8 22.4-4.1 22.4-15.5l0-377.4c0-4.2-1.6-8.4-5-11C247.4 52 202.4 32 144 32C93.5 32 46.3 45.3 18.1 56.1C6.8 60.5 0 71.7 0 83.8L0 454.1c0 11.9 12.8 20.2 24.1 16.5C55.6 460.1 105.5 448 144 448c33.9 0 79 14 105.6 23.5zm76.8 0C353 462 398.1 448 432 448c38.5 0 88.4 12.1 119.9 22.6c11.3 3.8 24.1-4.6 24.1-16.5l0-370.3c0-12.1-6.8-23.3-18.1-27.6C529.7 45.3 482.5 32 432 32c-58.4 0-103.4 20-123 35.6c-3.3 2.6-5 6.8-5 11L304 456c0 11.4 11.7 19.3 22.4 15.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Recipe Discovery</h3>
                    <p class="text-gray">Get personalized suggestions based on your available ingredients.</p>
                </div>
                <div class="group p-8 rounded-2xl border border-gray hover:border-primary transition-all hover:shadow-lg hover:shadow-primary">
                    <div class="w-14 h-14 bg-primary rounded-xl flex items-center justify-center mb-6 group-hover:bg-primary transition-colors">
                        <svg class="w-9 h-9 fill-white/80" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                            <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                            <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Share Your Recipes</h3>
                    <p class="text-gray">Contribute to our growing recipe database by sharing your favorite dishes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works -->
    <section id="how-it-works" class="py-20 bg-gradient-to-b from-background to-primary/40 overflow-hidden">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-16">How it Works</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $steps = [
                        [
                            'step' => '01',
                            'title' => 'Track Your Pantry',
                            'description' => 'Quickly add all ingredients',
                        ],
                        [
                            'step' => '02',
                            'title' => 'Discover Recipes',
                            'description' => 'Get inspired by suggestions',
                        ],
                        [
                            'step' => '03',
                            'title' => 'Start Cooking',
                            'description' => 'Enjoy delicious meals',
                        ]
                    ];
                @endphp

                @foreach($steps as $step)
                    <div class="group relative p-6 rounded-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-2">
                        <div class="text-5xl font-bold text-primary/20 absolute -top-8 -left-4">{{ $step['step'] }}</div>
                        <h3 class="text-xl font-semibold mb-3">{{ $step['title'] }}</h3>
                        <p class="text-gray-600">{{ $step['description'] }}</p>
                        @if(!$loop->last)
                            <div class="hidden md:block absolute top-1/2 right-0 w-16 h-0.5 bg-gray-200 transform translate-x-8"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-20 bg-gradient-to-b from-primary/40 to-primary/60 overflow-hidden">
        <h2 class="text-3xl font-bold text-center mb-16">What Our Users Say</h2>
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="grid md:grid-cols-2 gap-8">
                    @foreach([
                        ['quote' => 'Recipe Finder transformed how I cook. I save over $100 monthly.', 'author' => 'Mike R.'],
                        ['quote' => 'Finally, an application that helps me use everything in my fridge!', 'author' => 'Emma S.'],
                    ] as $testimonial)
                    <div class="p-8 rounded-2xl bg-white border border-gray hover:border-primary transition-all">
                        <div class="flex gap-4 mb-4">
                            @for($i = 0; $i < 5; $i++)
                            <svg class="w-5 h-5 text-yellow" fill="currentColor" viewBox="0 0 576 512" xmlns="http://www.w3.org/2000/svg">
                                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>                            </svg>
                            @endfor
                        </div>
                        <p class="text-gray mb-4">"{{ $testimonial['quote'] }}"</p>
                        <p class="text-gray font-semibold italic">{{ $testimonial['author'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <x-footer/>
</body>
</html>
