<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                    <i class="fas fa-file-alt mr-2 text-blue-600 dark:text-blue-400"></i> My Procurement Requests
                </h1>
                <a href="{{ route('marketplace.admin.store') }}"
                    class="mt-4 sm:mt-0 bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 shadow-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Listings
                </a>
            </div>

            @if ($requests->isNotEmpty())
                <!-- Requests List -->
                <div class="space-y-8">
                    @foreach ($requests as $request)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                            <div
                                class="bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 p-4 flex justify-between items-center">
                                <h2 class="text-xl font-semibold text-white">
                                    Request #{{ $request->id }} - {{ $request->created_at->format('M d, Y H:i') }}
                                </h2>
                                <div class="flex gap-2">
                                    @if ($request->status === 'Canceled')
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-medium text-white
                                        {{ $request->status === 'Pending' ? 'bg-yellow-500' : ($request->status === 'Canceled' ? 'bg-red-500' : 'bg-green-500') }}">
                                            {{ $request->status }}
                                        </span>
                                    @endif
                                    @if ($request->status !== 'Canceled')
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-medium text-white
                                        {{ $request->approval_status === 'Pending Approval' ? 'bg-yellow-700' : ($request->approval_status === 'Rejected' ? 'bg-red-700' : 'bg-green-700') }}">
                                            {{ $request->approval_status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead
                                        class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase">
                                        <tr>
                                            <th class="px-6 py-3 font-medium text-xs">Product</th>
                                            <th class="px-6 py-3 font-medium text-xs">Vendor</th>
                                            <th class="px-6 py-3 font-medium text-xs">Price</th>
                                            <th class="px-6 py-3 font-medium text-xs">Quantity</th>
                                            <th class="px-6 py-3 font-medium text-xs">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($request->products as $product)
                                            <tr
                                                class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                                <td
                                                    class="px-6 py-4 text-gray-900 dark:text-gray-100 truncate max-w-xs">
                                                    {{ $product->name }}</td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                    {{ $product->vendor->user->name }}</td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                    ₱{{ number_format($product->pivot->price, 2) }}</td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                    {{ $product->pivot->quantity }}</td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300 font-medium">
                                                    ₱{{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div
                                class="p-4 flex justify-between items-center border-t border-gray-200 dark:border-gray-700">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Total: ₱{{ number_format($request->total, 2) }}
                                </p>
                                @if ($request->status === 'Pending' && $request->approval_status === 'Pending Approval')
                                    <form action="{{ route('marketplace.admin.orders.cancel', $request->id) }}"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to cancel Request #{{ $request->id }}?');">
                                        @csrf
                                        <button type="submit"
                                            class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600 flex items-center">
                                            <i class="fas fa-times mr-2"></i> Cancel Request
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty Requests State -->
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                    <i class="fas fa-file-alt text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-xl text-gray-700 dark:text-gray-300 font-medium">You have no procurement requests
                        yet.</p>
                    <a href="{{ route('marketplace.admin.store') }}"
                        class="mt-6 inline-block bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 shadow-md">
                        <i class="fas fa-shopping-cart mr-2"></i> Start Requesting
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
