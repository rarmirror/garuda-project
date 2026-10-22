<nav class="absolute top-0 left-0 right-0 z-20">
    <div class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">

        <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-xl">
            <span class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white">✈</span>
            Garuda
        </a>

        <div class="hidden md:flex items-center gap-8 font-medium">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Flights</a>
            <a href="{{ route('hotels.index') }}" class="hover:text-blue-600">Hotels</a>
            <a href="{{ route('schedule.index') }}" class="hover:text-blue-600">Schedule</a>
            <a href="{{ route('testimonials.index') }}" class="hover:text-blue-600">Testimonials</a>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('contact.index') }}"
               class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-full border border-gray-300 text-sm font-medium hover:bg-gray-50">
                📞 Call Us
            </a>
            <a href="{{ route('my-booking.index') }}"
               class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-900 text-white text-sm font-medium hover:bg-gray-800">
                📅 My Booking
            </a>
        </div>

    </div>
</nav>