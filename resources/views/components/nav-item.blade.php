<a href="{{ $href }}" class="text-sm font-medium truncate md:text-base {{ $attributes->merge(['class' => '']) }}">
    <div
        class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 transition-colors {{ $active ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"></path>
        </svg>
        {{ $slot }}
    </div>
</a>
