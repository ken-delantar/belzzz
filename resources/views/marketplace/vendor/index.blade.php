<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white"></h3>
                <div class="mt-4 sm:mt-0 flex gap-4">
                    <a href="{{ route('marketplace.vendor.orders') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600 flex items-center">
                        <i class="fas fa-shopping-bag mr-2"></i> Procured Services/Items
                    </a>
                    <button id="open-product-modal"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Add New Service/Item
                    </button>
                </div>
            </div>

            <!-- Vendor Product Table -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">My Listings</h2>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
                    <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Name</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Type</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Price</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Stock</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($vendorProducts as $product)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-4 py-4 sm:px-6 text-gray-900 dark:text-gray-100 truncate max-w-xs">
                                        {{ $product->name }}</td>
                                    <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                        {{ ucfirst($product->type) }}</td>
                                    <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                        ₱{{ number_format($product->price, 2) }}</td>
                                    <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                        {{ $product->type === 'items' ? $product->stock : 'N/A' }}</td>
                                    <td class="px-4 py-4 sm:px-6 flex gap-2">
                                        <button id="edit-product-{{ $product->id }}"
                                            class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">Edit</button>
                                        <form action="{{ route('marketplace.vendor.products.destroy', $product->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                        No products/services found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Product Create Modal -->
            <x-modal name="productModal" :show="false" focusable maxWidth="2xl"
                class="flex items-center justify-center">
                <div class="p-6 bg-white rounded-lg dark:bg-gray-800 w-full max-w-3xl">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Add New Service/Item</h2>
                    <form action="{{ route('marketplace.vendor.products.store') }}" method="POST"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @csrf
                        <div class="col-span-1">
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-1">
                            <label for="type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Select Type</option>
                                <option value="service">Service</option>
                                <option value="items">Items/Products</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-1">
                            <label for="price"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price (₱)</label>
                            <input type="number" name="price" id="price" step="0.01" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            @error('price')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-1" id="stock-field" class="hidden">
                            <label for="stock"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="stock" id="stock" min="0"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            @error('stock')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-2">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                rows="3"></textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-span-2 flex justify-end gap-3">
                            <button type="button" id="close-product-modal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">Save</button>
                        </div>
                    </form>
                </div>
            </x-modal>

            <!-- Product Edit Modal -->
            @foreach ($vendorProducts as $product)
                <x-modal name="edit-product-modal-{{ $product->id }}" :show="false" focusable maxWidth="2xl"
                    class="flex items-center justify-center">
                    <div class="p-6 bg-white rounded-lg dark:bg-gray-800 w-full max-w-3xl">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Edit Product/Service</h2>
                        <form action="{{ route('marketplace.vendor.products.update', $product->id) }}" method="POST"
                            class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @csrf
                            @method('PUT')
                            <div class="col-span-1">
                                <label for="name-{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                <input type="text" name="name" id="name-{{ $product->id }}"
                                    value="{{ $product->name }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div class="col-span-1">
                                <label for="type-{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                                <select name="type" id="type-{{ $product->id }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="service" {{ $product->type === 'service' ? 'selected' : '' }}>
                                        Service</option>
                                    <option value="items" {{ $product->type === 'items' ? 'selected' : '' }}>
                                        Items/Products</option>
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label for="price-{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Price
                                    (₱)
                                </label>
                                <input type="number" name="price" id="price-{{ $product->id }}" step="0.01"
                                    value="{{ $product->price }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div class="col-span-1" id="stock-field-{{ $product->id }}"
                                class="{{ $product->type === 'service' ? 'hidden' : '' }}">
                                <label for="stock-{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stock <span
                                        class="text-red-500 {{ $product->type === 'items' ? '' : 'hidden' }}">*</span></label>
                                <input type="number" name="stock" id="stock-{{ $product->id }}"
                                    value="{{ $product->stock }}" min="0"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                            </div>
                            <div class="col-span-2">
                                <label for="description-{{ $product->id }}"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" id="description-{{ $product->id }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    rows="3">{{ $product->description }}</textarea>
                            </div>
                            <div class="col-span-2 flex justify-end gap-3">
                                <button type="button" id="close-edit-product-{{ $product->id }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">Update</button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            @endforeach
        </div>
    </div>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Product Create Modal
            const openProductModal = document.getElementById('open-product-modal');
            const closeProductModal = document.getElementById('close-product-modal');
            if (openProductModal) openProductModal.addEventListener('click', () => window.dispatchEvent(
                new CustomEvent('open-modal', {
                    detail: 'productModal'
                })));
            if (closeProductModal) closeProductModal.addEventListener('click', () => window.dispatchEvent(
                new CustomEvent('close-modal', {
                    detail: 'productModal'
                })));

            // Dynamic Stock Field for Create
            const typeSelect = document.getElementById('type');
            const stockField = document.getElementById('stock-field');
            const stockInput = document.getElementById('stock');
            if (typeSelect && stockField && stockInput) {
                typeSelect.addEventListener('change', function() {
                    stockField.classList.toggle('hidden', this.value === 'service');
                    stockInput.required = this.value === 'items';
                    stockInput.value = this.value === 'service' ? '' : stockInput
                        .value; // Clear stock for services
                });
            }

            // Product Edit Modals
            @foreach ($vendorProducts as $product)
                const openEdit{{ $product->id }} = document.getElementById('edit-product-{{ $product->id }}');
                const closeEdit{{ $product->id }} = document.getElementById(
                    'close-edit-product-{{ $product->id }}');
                if (openEdit{{ $product->id }}) openEdit{{ $product->id }}.addEventListener('click', () =>
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: 'edit-product-modal-{{ $product->id }}'
                    })));
                if (closeEdit{{ $product->id }}) closeEdit{{ $product->id }}.addEventListener('click', () =>
                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: 'edit-product-modal-{{ $product->id }}'
                    })));

                const typeEdit{{ $product->id }} = document.getElementById('type-{{ $product->id }}');
                const stockFieldEdit{{ $product->id }} = document.getElementById(
                    'stock-field-{{ $product->id }}');
                const stockInputEdit{{ $product->id }} = document.getElementById('stock-{{ $product->id }}');
                if (typeEdit{{ $product->id }} && stockFieldEdit{{ $product->id }} &&
                    stockInputEdit{{ $product->id }}) {
                    typeEdit{{ $product->id }}.addEventListener('change', function() {
                        stockFieldEdit{{ $product->id }}.classList.toggle('hidden', this.value ===
                            'service');
                        stockInputEdit{{ $product->id }}.required = this.value === 'items';
                        stockInputEdit{{ $product->id }}.value = this.value === 'service' ? '' :
                            stockInputEdit{{ $product->id }}.value;
                    });
                }
            @endforeach
        });
    </script>
</x-app-layout>
