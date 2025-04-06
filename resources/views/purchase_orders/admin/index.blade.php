<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')

            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 dark:bg-gray-800 dark:text-white">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-semibold">
                            <a href="{{ route('purchase_orders.index') }}"
                                class="text-blue-600 hover:text-blue-800 transition-colors dark:text-blue-400 dark:hover:text-blue-300">
                                Purchase Orders
                            </a>
                        </h2>
                        <div class="flex justify-end">
                            <button id="open-po-modal"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700">
                                New Purchase Order
                            </button>
                        </div>
                    </div>

                    <!-- Improved Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        PO Number</th>
                                    <th
                                        class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Vendor</th>
                                    <th
                                        class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Amount</th>
                                    <th
                                        class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Status</th>
                                    <th
                                        class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($purchaseOrders as $index => $po)
                                    <tr
                                        class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} hover:bg-gray-100 transition-colors dark:bg-gray-800 dark:hover:bg-gray-700">
                                        <td class="px-4 py-4 sm:px-6 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $po->po_number }}</td>
                                        <td class="px-4 py-4 sm:px-6 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $po->vendor->user->name ?? 'Unassigned' }}</td>
                                        <td class="px-4 py-4 sm:px-6 text-sm text-gray-700 dark:text-gray-300">
                                            ₱{{ number_format($po->amount, 2) }}</td>
                                        <td class="px-4 py-4 sm:px-6 text-sm">
                                            @php
                                                switch ($po->status) {
                                                    case 'Approved':
                                                        $statusClasses =
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                                        break;
                                                    case 'Rejected':
                                                        $statusClasses =
                                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                                        break;
                                                    case 'Pending':
                                                        $statusClasses =
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                                                        break;
                                                    default:
                                                        $statusClasses =
                                                            'bg-gray-100 text-gray-600 dark:bg-gray-600 dark:text-gray-200';
                                                        break;
                                                }
                                                $statusText = ucfirst($po->status);
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 sm:px-6 text-sm">
                                            <button type="button" id="view-po-{{ $po->id }}"
                                                class="bg-gray-600 text-white px-3 py-1 rounded-md hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-500 dark:hover:bg-gray-600">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white dark:bg-gray-800">
                                        <td colspan="5"
                                            class="px-4 py-12 sm:px-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No purchase orders found. Start by creating a new PO!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($purchaseOrders->hasPages())
                        <div
                            class="mt-6 flex justify-between items-center px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Showing {{ $purchaseOrders->firstItem() }} to {{ $purchaseOrders->lastItem() }} of
                                {{ $purchaseOrders->total() }} purchase orders
                            </div>
                            <div class="flex gap-2">
                                @if ($purchaseOrders->onFirstPage())
                                    <span
                                        class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-600 dark:text-gray-400">Previous</span>
                                @else
                                    <a href="{{ $purchaseOrders->previousPageUrl() }}"
                                        class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">Previous</a>
                                @endif

                                @foreach ($purchaseOrders->links()->elements[0] as $page => $url)
                                    @if ($page == $purchaseOrders->currentPage())
                                        <span
                                            class="px-3 py-1 bg-blue-600 text-white rounded-md dark:bg-blue-700">{{ $page }}</span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">{{ $page }}</a>
                                    @endif
                                @endforeach

                                @if ($purchaseOrders->hasMorePages())
                                    <a href="{{ $purchaseOrders->nextPageUrl() }}"
                                        class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">Next</a>
                                @else
                                    <span
                                        class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-600 dark:text-gray-400">Next</span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Create PO Modal (Centered) -->
            <x-modal name="purchaseOrderModal" :show="false" focusable maxWidth="lg"
                class="flex items-center justify-center">
                <div class="p-6 bg-white rounded-lg dark:bg-gray-800">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Create Purchase Order</h2>
                    <form action="{{ route('purchase_orders.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="po_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PO
                                Number</label>
                            <input type="text" name="po_number" id="po_number" value="{{ $poNumber ?? 'N/A' }}"
                                readonly
                                class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated by the system.</p>
                        </div>
                        <div>
                            <label for="vendor_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Vendor</label>
                            <select name="vendor_id" id="vendor_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Select a Vendor</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->firstname }}
                                        {{ $vendor->lastname }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="description" id="description" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white resize-y"
                                rows="4" placeholder="Enter details about the purchase order">{{ trim(old('description')) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="amount"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount (₱)</label>
                            <input type="number" name="amount" id="amount" step="0.01" required
                                value="{{ old('amount') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                placeholder="0.00">
                            @error('amount')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" id="close-po-modal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>

            <!-- View PO Details Modal (Centered) -->
            @foreach ($purchaseOrders as $po)
                <x-modal name="view-po-modal-{{ $po->id }}" :show="false" focusable maxWidth="md"
                    class="flex items-center justify-center">
                    <div class="p-6 bg-white rounded-lg shadow-lg dark:bg-gray-800">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Purchase Order Details</h2>
                            <button id="close-view-po-{{ $po->id }}"
                                class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-4">
                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">PO Number</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100">{{ $po->po_number }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Vendor</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-gray-100">{{ $po->vendor->user->name ?? 'Unassigned' }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Amount</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-gray-100">₱{{ number_format($po->amount, 2) }}</span>
                            </div>
                            <div
                                class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <div
                                class="flex items-start justify-between border-b border-gray-200 dark:border-gray-700 pb-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Description</span>
                                <span
                                    class="text-sm text-gray-900 dark:text-gray-100 max-w-xs text-right">{{ $po->description }}</span>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button id="close-view-po-footer-{{ $po->id }}"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                Close
                            </button>
                        </div>
                    </div>
                </x-modal>
            @endforeach
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create PO Modal
            const openButton = document.getElementById('open-po-modal');
            const closeButton = document.getElementById('close-po-modal');

            if (openButton) {
                openButton.addEventListener('click', function() {
                    console.log('Opening create modal');
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: 'purchaseOrderModal'
                    }));
                });
            }

            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    console.log('Closing create modal');
                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: 'purchaseOrderModal'
                    }));
                });
            }

            // View PO Modals
            @foreach ($purchaseOrders as $po)
                const openViewButton{{ $po->id }} = document.getElementById('view-po-{{ $po->id }}');
                const closeViewButton{{ $po->id }} = document.getElementById(
                    'close-view-po-{{ $po->id }}');
                const closeViewFooterButton{{ $po->id }} = document.getElementById(
                    'close-view-po-footer-{{ $po->id }}');

                if (openViewButton{{ $po->id }}) {
                    openViewButton{{ $po->id }}.addEventListener('click', function() {
                        console.log('Opening view modal for PO {{ $po->id }}');
                        window.dispatchEvent(new CustomEvent('open-modal', {
                            detail: 'view-po-modal-{{ $po->id }}'
                        }));
                    });
                }

                if (closeViewButton{{ $po->id }}) {
                    closeViewButton{{ $po->id }}.addEventListener('click', function() {
                        console.log('Closing view modal for PO {{ $po->id }}');
                        window.dispatchEvent(new CustomEvent('close-modal', {
                            detail: 'view-po-modal-{{ $po->id }}'
                        }));
                    });
                }

                if (closeViewFooterButton{{ $po->id }}) {
                    closeViewFooterButton{{ $po->id }}.addEventListener('click', function() {
                        console.log('Closing view modal for PO {{ $po->id }} from footer');
                        window.dispatchEvent(new CustomEvent('close-modal', {
                            detail: 'view-po-modal-{{ $po->id }}'
                        }));
                    });
                }
            @endforeach
        });
    </script>
</x-app-layout>
