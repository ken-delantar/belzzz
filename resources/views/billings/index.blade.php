<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 bg-gray-50 min-h-screen">
        @include('navigation.header')
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 sm:gap-8">

            <!-- Center Column: Billing and Invoicing -->
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Billing and Invoicing</h2>
                    <button onclick="openModal()"
                        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm sm:text-base font-semibold">
                        <span class="text-lg sm:text-xl">+</span> Create Invoice
                    </button>
                </div>

                <!-- Invoice Table -->
                <div class="overflow-x-auto shadow-md rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Invoice #</th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50 transition cursor-pointer"
                                onclick="openCardModal('cardModal1')">
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-900 font-medium">
                                    INV-001</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-700">
                                    2024-03-15</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-700">
                                    $1,500.00</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-block px-2 sm:px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-medium">Paid</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50 transition cursor-pointer"
                                onclick="openCardModal('cardModal2')">
                                <td
                                    class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-900 font-medium">
                                    INV-002</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-700">
                                    2024-03-20</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm sm:text-base text-gray-700">
                                    $2,300.00</td>
                                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-block px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs sm:text-sm font-medium">Pending</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Sidebar: Quick Stats -->
            <div class="w-full lg:w-80 space-y-6 sm:space-y-8">
                <!-- Billing Alerts -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-red-500 flex-shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Billing Alerts
                    </h2>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                            <h3 class="text-red-800 font-semibold text-sm sm:text-base">Overdue Invoice</h3>
                            <p class="text-red-600 text-xs sm:text-sm">INV-003 - Due 2024-03-10</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                            <h3 class="text-yellow-800 font-semibold text-sm sm:text-base">Pending Payment</h3>
                            <p class="text-yellow-600 text-xs sm:text-sm">INV-002 - Due in 2 days</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Quick Stats</h2>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm sm:text-base">Total Invoiced</span>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base">$5,800.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm sm:text-base">Pending Amount</span>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base">$2,300.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm sm:text-base">Paid This Month</span>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base">$3,500.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Create Invoice Modal -->
    <div id="scheduleModal" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 w-full h-full bg-black bg-opacity-60 hidden">
        <div class="flex justify-center items-center min-h-screen p-4 sm:p-6">
            <div
                class="relative w-full max-w-md mx-auto my-4 sm:my-6 max-h-[90vh] overflow-y-auto rounded-xl shadow-xl bg-white">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Create Invoice</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 sm:w-10 sm:h-10 inline-flex justify-center items-center transition-colors"
                            onclick="closeModal()" aria-label="Close modal">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                        <form id="invoiceForm">
                            <div class="space-y-4 sm:space-y-6">
                                <div>
                                    <label for="invoice-id"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-1 sm:mb-2">Invoice
                                        #</label>
                                    <input type="text" id="invoice-id" name="invoice-id"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-md text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="INV-XXX" required>
                                </div>
                                <div>
                                    <label for="invoice-date"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-1 sm:mb-2">Date</label>
                                    <input type="date" id="invoice-date" name="invoice-date"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-md text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label for="invoice-amount"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-1 sm:mb-2">Amount</label>
                                    <input type="text" id="invoice-amount" name="invoice-amount"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-md text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="$0.00" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div
                        class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50 space-x-2 sm:space-x-3">
                        <button type="button"
                            class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeModal()">Cancel</button>
                        <button type="submit" form="invoiceForm"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Detail Modals -->
    <div id="cardModal1" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 w-full h-full bg-black bg-opacity-60 hidden">
        <div class="flex justify-center items-center min-h-screen p-4 sm:p-6">
            <div
                class="relative w-full max-w-md mx-auto my-4 sm:my-6 max-h-[90vh] overflow-y-auto rounded-xl shadow-xl bg-white">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Invoice Details</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 sm:w-10 sm:h-10 inline-flex justify-center items-center transition-colors"
                            onclick="closeCardModal('cardModal1')" aria-label="Close modal">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="space-y-2 sm:space-y-3">
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Invoice #:</strong> INV-001</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Date:</strong> 2024-03-15</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Amount:</strong> $1,500.00</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Status:</strong> Paid</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCardModal('cardModal1')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="cardModal2" tabindex="-1" aria-hidden="true"
        class="fixed inset-0 z-50 w-full h-full bg-black bg-opacity-60 hidden">
        <div class="flex justify-center items-center min-h-screen p-4 sm:p-6">
            <div
                class="relative w-full max-w-md mx-auto my-4 sm:my-6 max-h-[90vh] overflow-y-auto rounded-xl shadow-xl bg-white">
                <div class="flex flex-col">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Invoice Details</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 sm:w-10 sm:h-10 inline-flex justify-center items-center transition-colors"
                            onclick="closeCardModal('cardModal2')" aria-label="Close modal">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="space-y-2 sm:space-y-3">
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Invoice #:</strong> INV-002</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Date:</strong> 2024-03-20</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Amount:</strong> $2,300.00</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Status:</strong> Pending</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCardModal('cardModal2')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        function openCardModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeCardModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            const modals = ['scheduleModal', 'cardModal1', 'cardModal2'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    const modalContent = modal.querySelector('.relative');
                    if (e.target === modal && !modalContent.contains(e.target)) {
                        modal.classList.add('hidden');
                    }
                }
            });
        });

        // Prevent form submission for demo (remove in production)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Form submitted (demo mode)');
                closeModal();
            });
        });
    </script>
</x-app-layout>
