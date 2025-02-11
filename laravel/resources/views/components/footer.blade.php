@props([
    'backgroundClasses' => 'bg-gradient-to-b from-primary/60 to-primary overflow-hidden text-white'

])
<footer class="{{ $backgroundClasses }}">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-bold  text-xl mb-4">Recipe Finder</h4>
                <p >Making sustainable cooking easy.</p>
            </div>
            <div>
                <h4 class="font-bold text-xl mb-4">Company</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('imprint') }}" class="hover:underline">Legal Notice</a></li>
                    <li><a href="{{ route('privacypolicy') }}" class=" hover:underline">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white mt-5 pb-3 pt-8 text-center">
            <p>&copy; {{ date('Y') }} Recipe Finder. All rights reserved.</p>
        </div>
    </div>
</footer>
