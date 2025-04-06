<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-8 animate-fade-in">
                <h1
                    class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight flex items-center">
                    <i class="fas fa-check-circle mr-2 text-yellow-600 dark:text-yellow-400 animate-pulse"></i>
                    Approval Requests
                    <span class="ml-2 text-sm font-normal text-gray-600 dark:text-gray-400">({{ $orders->count() }}
                        Pending)</span>
                </h1>
                <a href="{{ route('marketplace.admin.store') }}"
                    class="mt-4 sm:mt-0 bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 shadow-md flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Procurement Listings
                </a>
            </div>

            <!-- Filter/Search Bar -->
            @if ($orders->isNotEmpty())
                <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <i
                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                        <input type="text" id="search" placeholder="Search by user or product..."
                            class="w-full pl-10 p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <select id="sort"
                        class="p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="date-desc">Newest First</option>
                        <option value="date-asc">Oldest First</option>
                        <option value="user">By User (A-Z)</option>
                    </select>
                </div>

                <!-- Approval Requests List -->
                <div class="space-y-6">
                    @foreach ($orders as $order)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-2xl">
                            <!-- Request Header (Collapsible Toggle) -->
                            <button type="button"
                                class="w-full toggle-collapse bg-gradient-to-r from-yellow-500 to-orange-600 dark:from-yellow-600 dark:to-orange-700 p-4 flex justify-between items-center focus:outline-none"
                                data-target="collapse-{{ $order->id }}">
                                <h2 class="text-xl font-semibold text-white flex items-center">
                                    <i class="fas fa-file-alt mr-2"></i> Request #{{ $order->id }} by
                                    {{ $order->user->name }}
                                    <span
                                        class="ml-2 text-sm font-normal">({{ $order->created_at->diffForHumans() }})</span>
                                </h2>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-3 py-1 rounded-full text-sm font-medium text-white bg-yellow-700 dark:bg-yellow-800">
                                        {{ $order->approval_status }}
                                    </span>
                                    <i class="fas fa-chevron-down text-white transition-transform duration-300"></i>
                                </div>
                            </button>

                            <!-- Collapsible Content -->
                            <div id="collapse-{{ $order->id }}" class="collapse-content hidden">
                                <div class="overflow-x-auto">
                                    <table
                                        class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
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
                                            @foreach ($order->products as $product)
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
                                    class="p-4 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Total: ₱{{ number_format($order->total, 2) }}
                                    </p>
                                    <div class="flex gap-3">
                                        <form action="{{ route('marketplace.admin.orders.approve', $order->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Approve Request"
                                                class="bg-green-600 text-white px-4 py-2 rounded-full hover:bg-green-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 flex items-center shadow-md">
                                                <i class="fas fa-check mr-2"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('marketplace.admin.orders.reject', $order->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            <button type="submit" title="Reject Request"
                                                class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600 flex items-center shadow-md">
                                                <i class="fas fa-times mr-2"></i> Reject
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl shadow-lg animate-fade-in">
                    <i
                        class="fas fa-exclamation-circle text-6xl text-gray-400 dark:text-gray-500 mb-4 animate-bounce"></i>
                    <p class="text-xl text-gray-700 dark:text-gray-300 font-medium mb-2">No Pending Approval Requests
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Looks like everything’s up to date!</p>
                    <a href="{{ route('marketplace.admin.store') }}"
                        class="mt-6 inline-block bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 shadow-md flex items-center mx-auto">
                        <i class="fas fa-arrow-left mr-2"></i> Explore Procurement Listings
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Tailwind CSS Animations -->
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        .animate-bounce {
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        .collapse-content.hidden {
            display: none;
        }
    </style>

    <!-- JavaScript for Collapsible Cards and Filter/Search -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toggle Collapsible Cards
            document.querySelectorAll('.toggle-collapse').forEach(button => {
                button.addEventListener('click', () => {
                    const targetId = button.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const chevron = button.querySelector('.fa-chevron-down');
                    content.classList.toggle('hidden');
                    chevron.classList.toggle('rotate-180');
                });
            });

            // Search Functionality
            const searchInput = document.getElementById('search');
            const cards = document.querySelectorAll('.bg-white, .dark\\:bg-gray-800');
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                cards.forEach(card => {
                    const user = card.querySelector('h2').textContent.toLowerCase();
                    card.style.display = user.includes(query) ? '' : 'none';
                });
            });

            // Sort Functionality
            const sortSelect = document.getElementById('sort');
            sortSelect.addEventListener('change', () => {
                const sortValue = sortSelect.value;
                const container = document.querySelector('.space-y-6');
                const items = Array.from(container.children);

                items.sort((a, b) => {
                    const aDate = new Date(a.querySelector('h2 span').textContent);
                    const bDate = new Date(b.querySelector('h2 span').textContent);
                    const aUser = a.querySelector('h2').textContent.toLowerCase();
                    const bUser = b.querySelector('h2').textContent.toLowerCase();

                    if (sortValue === 'date-desc') return bDate - aDate;
                    if (sortValue === 'date-asc') return aDate - bDate;
                    if (sortValue === 'user') return aUser.localeCompare(bUser);
                    return 0;
                });

                items.forEach(item => container.appendChild(item));
            });
        });
    </script>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
