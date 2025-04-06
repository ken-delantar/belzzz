<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white"></h2>
                <div class="mt-4 sm:mt-0 flex items-center gap-4">
                    @if (Auth::user()->role === 'Admin')
                        <a href="{{ route('marketplace.admin.orders') }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 dark:text-gray-300 dark:hover:text-blue-400 font-medium transition duration-200 flex items-center">
                            <i class="fas fa-list mr-2"></i> View All Orders
                        </a>
                        <a href="{{ route('marketplace.admin.approval_requests') }}"
                            class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 transition duration-200 focus:outline-none focus:ring-2 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i> Review Requests ({{ $pendingApprovalCount }})
                        </a>
                    @elseif (Auth::user()->role === 'Staff')
                        <a href="{{ route('marketplace.admin.staff_requests') }}"
                            class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-200 focus:outline-none focus:ring-2 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i> Track My Requests
                        </a>
                    @endif
                    <a href="{{ route('marketplace.admin.cart') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 flex items-center">
                        <i class="fas fa-shopping-cart mr-2 text-white dark:text-gray-400"></i>
                        View Cart ({{ $cartItems->count() }})
                    </a>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1 relative">
                        <i
                            class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                        <input type="text" id="search" placeholder="Search items/services..."
                            class="w-full pl-10 p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex gap-2">
                        <select id="type-filter"
                            class="p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Types</option>
                            <option value="service">Services</option>
                            <option value="items">Items/Products</option>
                        </select>
                        <select id="price-filter"
                            class="p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sort by Price</option>
                            <option value="low-high">Low to High</option>
                            <option value="high-low">High to Low</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Product Listings -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="product-list">
                @forelse ($products as $product)
                    <div class="bg-white rounded-lg shadow-md p-4 dark:bg-gray-800 transition-transform transform hover:scale-105 duration-200 product-card"
                        data-type="{{ $product->type }}" data-price="{{ $product->price }}">
                        <div class="flex flex-col h-full">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                {{ $product->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 mt-1 flex-1 line-clamp-2">
                                {{ $product->description }}</p>
                            <p class="text-gray-800 dark:text-gray-100 mt-2 font-medium">
                                ₱{{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                <i class="fas fa-store mr-1"></i> {{ $product->vendor->user->name }}
                            </p>
                            @if ($product->type === 'items')
                                <p
                                    class="text-sm mt-1 {{ $product->stock <= 0 ? 'text-red-500' : 'text-gray-600 dark:text-gray-300' }} flex items-center">
                                    <i class="fas fa-box mr-1"></i> Stock:
                                    {{ $product->stock > 0 ? $product->stock : 'Out of Stock' }}
                                </p>
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1 flex items-center">
                                    <i class="fas fa-tools mr-1"></i> Service Available
                                </p>
                            @endif
                            <div class="mt-4 flex gap-2">
                                @if ($product->type === 'items' && $product->stock <= 0)
                                    <span
                                        class="inline-flex items-center justify-center w-full py-2 bg-gray-300 text-gray-600 rounded-md cursor-not-allowed">
                                        <i class="fas fa-ban mr-2"></i> Out of Stock
                                    </span>
                                @else
                                    <form action="{{ route('marketplace.admin.cart.buy') }}" method="POST"
                                        class="flex-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit"
                                            class="w-full bg-green-600 text-white p-2 rounded-md hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 flex items-center justify-center"
                                            title="{{ Auth::user()->role === 'Staff' ? 'Request Item/Service' : 'Purchase Item/Service' }}">
                                            <i class="fas fa-shopping-bag mr-1"></i>
                                            <span class="text-sm">
                                                @if (Auth::user()->role === 'Staff')
                                                    {{ $product->type === 'service' ? 'Request Service' : 'Request Item' }}
                                                @else
                                                    {{ $product->type === 'service' ? 'Purchase Service' : 'Purchase Item' }}
                                                @endif
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10">
                        <i class="fas fa-box-open text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-lg text-gray-700 dark:text-gray-300">No products available.</p>
                        <a href="{{ route('marketplace.admin.store') }}"
                            class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                            Reload Listings
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Add to Cart Modals -->
            @foreach ($products as $product)
                @if (!($product->type === 'items' && $product->stock <= 0))
                    <x-modal name="add-to-cart-modal-{{ $product->id }}" :show="false" focusable maxWidth="md"
                        class="flex items-center justify-center">
                        <div class="p-6 bg-white rounded-lg dark:bg-gray-800 w-full max-w-md">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                <i class="fas fa-cart-plus mr-2"></i>
                                {{ Auth::user()->role === 'Staff' ? 'Request ' : 'Add ' }}{{ $product->name }} to Cart
                            </h2>
                            <form action="{{ route('marketplace.admin.cart.add') }}" method="POST" class="space-y-6">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-info-circle mr-2"></i> Description
                                        </label>
                                        <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $product->description }}
                                        </p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-tag mr-2"></i> Price
                                        </label>
                                        <p class="mt-1 text-gray-800 dark:text-gray-100 font-medium">
                                            ₱{{ number_format($product->price, 2) }}</p>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-store mr-2"></i> Vendor
                                        </label>
                                        <p class="mt-1 text-gray-500 dark:text-gray-400">
                                            {{ $product->vendor->user->name }}</p>
                                    </div>
                                    <div>
                                        @if ($product->type === 'items')
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                                <i class="fas fa-box mr-2"></i> Stock
                                            </label>
                                            <p class="mt-1 text-gray-600 dark:text-gray-300">{{ $product->stock }}</p>
                                        @else
                                            <label
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                                <i class="fas fa-tools mr-2"></i> Availability
                                            </label>
                                            <p class="mt-1 text-gray-600 dark:text-gray-300">Service Available</p>
                                        @endif
                                    </div>
                                    <div>
                                        <label for="quantity-{{ $product->id }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center">
                                            <i class="fas fa-sort-numeric-up mr-2"></i> Quantity
                                        </label>
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button"
                                                onclick="adjustQuantity('quantity-{{ $product->id }}', -1)"
                                                class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">-</button>
                                            <input type="number" name="quantity" id="quantity-{{ $product->id }}"
                                                min="1"
                                                @if ($product->type === 'items') max="{{ $product->stock }}" @endif
                                                value="1" required
                                                class="w-20 p-2 rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center" />
                                            <button type="button"
                                                onclick="adjustQuantity('quantity-{{ $product->id }}', 1)"
                                                class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">+</button>
                                        </div>
                                        @error('quantity')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3">
                                    <button type="button" id="close-add-to-cart-{{ $product->id }}"
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                        Discard
                                    </button>
                                    <button type="submit"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 flex items-center">
                                        <i class="fas fa-cart-plus mr-2"></i>
                                        {{ Auth::user()->role === 'Staff' ? 'Submit Request' : 'Add to Cart' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </x-modal>
                @endif
            @endforeach
        </div>
    </div>

    <!-- Client-Side Filtering, Sorting, and Modal Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filter and Sort Logic
            const searchInput = document.getElementById('search');
            const typeFilter = document.getElementById('type-filter');
            const priceFilter = document.getElementById('price-filter');
            const productCards = document.querySelectorAll('.product-card');

            function filterAndSortProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const typeValue = typeFilter.value;
                const priceValue = priceFilter.value;

                let products = Array.from(productCards);
                products = products.filter(card => {
                    const name = card.querySelector('h3').textContent.toLowerCase();
                    const type = card.dataset.type;
                    return name.includes(searchTerm) && (!typeValue || type === typeValue);
                });

                if (priceValue) {
                    products.sort((a, b) => {
                        const priceA = parseFloat(a.dataset.price);
                        const priceB = parseFloat(b.dataset.price);
                        return priceValue === 'low-high' ? priceA - priceB : priceB - priceA;
                    });
                }

                productCards.forEach(card => card.style.display = 'none');
                products.forEach(card => card.style.display = 'block');
            }

            searchInput.addEventListener('input', filterAndSortProducts);
            typeFilter.addEventListener('change', filterAndSortProducts);
            priceFilter.addEventListener('change', filterAndSortProducts);

            // Modal Handling (Commented out since "Add to Cart" button is not present)
            /*
            // Modal Handling logic can be implemented here if needed.
            */
        });

        function adjustQuantity(inputId, change) {
            const input = document.getElementById(inputId);
            let value = parseInt(input.value) + change;
            if (value < parseInt(input.min)) value = input.min;
            if (input.max && value > parseInt(input.max)) value = input.max;
            input.value = value;
        }
    </script>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
