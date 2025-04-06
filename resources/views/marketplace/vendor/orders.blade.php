<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">My Procured Services/Items</h1>
                <a href="{{ route('marketplace.vendor.index') }}"
                    class="mt-4 sm:mt-0 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                </a>
            </div>

            <!-- Vendor-Specific Purchase Orders -->
            @if ($purchaseOrders->isNotEmpty())
                <div class="space-y-6">
                    @foreach ($purchaseOrders as $purchaseOrder)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl cursor-pointer"
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'po-modal-{{ $purchaseOrder->id }}' }))">
                            <div
                                class="bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 p-4 flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-white">
                                    PO #{{ $purchaseOrder->po_number }} -
                                    {{ $purchaseOrder->created_at->format('M d, Y H:i') }}
                                </h3>
                                <span
                                    class="px-3 py-1 rounded-full text-sm font-medium text-white
                                    {{ $purchaseOrder->status === 'Pending'
                                        ? 'bg-yellow-500'
                                        : ($purchaseOrder->status === 'Approved'
                                            ? 'bg-green-500'
                                            : ($purchaseOrder->status === 'Rejected'
                                                ? 'bg-red-500'
                                                : ($purchaseOrder->status === 'Completed'
                                                    ? 'bg-blue-500'
                                                    : 'bg-gray-500'))) }}">
                                    {{ $purchaseOrder->status }}
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead
                                        class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase">
                                        <tr>
                                            <th class="px-6 py-3 font-medium text-xs">Product</th>
                                            <th class="px-6 py-3 font-medium text-xs">Price</th>
                                            <th class="px-6 py-3 font-medium text-xs">Quantity</th>
                                            <th class="px-6 py-3 font-medium text-xs">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($purchaseOrder->products as $product)
                                            <tr
                                                class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                                <td
                                                    class="px-6 py-4 text-gray-900 dark:text-gray-100 truncate max-w-xs">
                                                    {{ $product['name'] }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                    ₱{{ number_format($product['price'], 2) }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                                    {{ $product['quantity'] }}
                                                </td>
                                                <td class="px-6 py-4 text-gray-700 dark:text-gray-300 font-medium">
                                                    ₱{{ number_format($product['total'], 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Subtotal: ₱{{ number_format($purchaseOrder->products->sum('total'), 2) }}
                                </p>
                            </div>
                        </div>

                        <!-- Purchase Order Modal -->
                        <x-modal name="po-modal-{{ $purchaseOrder->id }}" :show="false" focusable maxWidth="lg"
                            class="flex items-center justify-center">
                            <div class="p-6 bg-white rounded-lg dark:bg-gray-800 w-full max-w-2xl">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                    PO #{{ $purchaseOrder->po_number }}
                                </h2>
                                <p class="text-gray-700 dark:text-gray-300 mb-4">
                                    Current Status:
                                    <span
                                        class="font-medium {{ $purchaseOrder->status === 'Pending'
                                            ? 'text-yellow-600'
                                            : ($purchaseOrder->status === 'Approved'
                                                ? 'text-green-600'
                                                : ($purchaseOrder->status === 'Rejected'
                                                    ? 'text-red-600'
                                                    : ($purchaseOrder->status === 'Completed'
                                                        ? 'text-blue-600'
                                                        : 'text-gray-600'))) }}">
                                        {{ $purchaseOrder->status }}
                                    </span>
                                </p>
                                <div class="grid grid-cols-1 gap-4">
                                    @if ($purchaseOrder->status === 'Pending')
                                        <form
                                            action="{{ route('marketplace.vendor.orders.update-status', $purchaseOrder->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Approved">
                                            <button type="submit"
                                                class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600">
                                                Approve
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('marketplace.vendor.orders.update-status', $purchaseOrder->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="submit"
                                                class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600">
                                                Reject
                                            </button>
                                        </form>
                                    @elseif ($purchaseOrder->status === 'Approved')
                                        <form
                                            action="{{ route('marketplace.vendor.orders.update-status', $purchaseOrder->id) }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit"
                                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                                Mark as Completed
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: 'po-modal-{{ $purchaseOrder->id }}' }))"
                                    class="mt-4 w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                    Close
                                </button>
                            </div>
                        </x-modal>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg">
                    <i class="fas fa-box-open text-5xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-xl text-gray-700 dark:text-gray-300 font-medium">No purchase orders for your products
                        yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
