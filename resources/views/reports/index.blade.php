<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 bg-gray-50 min-h-screen">

        @include('navigation.header')

        <div class="max-w-7xl mx-auto p-6 md:p-12 bg-gray-50 min-h-screen flex flex-col md:flex-row gap-8">
            <!-- Average Rating Section -->
            <div class="w-full md:w-1/3">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Average Rating</h2>
                <div class="bg-white border border-gray-200 p-6 rounded-lg shadow-md">
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="text-4xl md:text-5xl font-bold text-gray-800">
                                {{ number_format($averageRating, 1) }}
                            </span>
                            <div class="flex text-yellow-400">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 md:w-6 md:h-6 {{ $i <= round($averageRating) ? 'text-yellow-400' : 'text-gray-300' }}"
                                        fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">Based on {{ $totalReviews }} Reviews</p>
                    </div>

                    <div class="space-y-3">
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="flex items-center gap-2">
                                <span class="w-4 text-sm text-gray-700">{{ $i }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-blue-500 h-2.5 rounded-full transition-all duration-300"
                                        style="width: {{ isset($ratingsCount[$i]) ? ($ratingsCount[$i] / $totalReviews) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <span class="w-12 text-sm text-gray-600">
                                    {{ isset($ratingsCount[$i]) ? round(($ratingsCount[$i] / $totalReviews) * 100) : 0 }}%
                                </span>
                            </div>
                        @endfor
                    </div>

                    <div class="mt-8">
                        <p class="text-gray-600 text-sm">Summarized ratings from our vendors, reflecting overall
                            customer
                            satisfaction.</p>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-2/3">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">Customer Feedback</h2>
                <div class="space-y-6">
                    @forelse ($reports as $feedback)
                        <div
                            class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                                        @if ($feedback->photo)
                                            <img src="{{ asset('storage/' . $feedback->photo) }}" alt="User Photo"
                                                class="w-full h-full object-cover rounded-full">
                                        @else
                                            <svg class="w-12 h-12 text-gray-500" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M12 2a5 5 0 11-5 5 5 5 0 015-5zm0 7a3 3 0 100-6 3 3 0 000 6zm-6 13a1 1 0 01-1-1v-1a7 7 0 0114 0v1a1 1 0 01-1 1H6zm-1-2v1h14v-1a5 5 0 00-10 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $feedback->name }}</h3>
                                        <p class="text-gray-500 text-sm">{{ $feedback->created_at->format('F d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"
                                            fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed">{{ $feedback->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <p class="text-gray-500 text-lg">No feedback found.</p>
                        </div>
                    @endforelse

                </div>
                <div class="mt-6">
                    {{ $reports->links() }} <!-- Laravel pagination links -->
                </div>
            </div>
        </div>

    </main>
</x-app-layout>
