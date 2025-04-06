<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            @include('navigation.header')

            <!-- Main Grid Content -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <!-- Contracts Section -->
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 dark:bg-gray-800 dark:text-white">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h2 class="text-lg sm:text-xl md:text-2xl font-semibold">
                            <a href="approved_list.html" class="text-blue-600 hover:text-blue-800 transition-colors">
                                Contracts
                            </a>
                        </h2>
                        <div class="flex justify-end">
                            <button id="openModal" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Upload Contract
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead class="dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Vendor Name
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Email
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Date
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Purpose
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Status
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Performed By
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Reason
                                    </th>
                                    <th
                                        class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse ($contracts as $contract)
                                    <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-gray-700">
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                            {{ $contract->vendor->user->name ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            {{ $contract->vendor->user->email ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            {{ $contract->created_at->format('Y-m-d') ?? '' }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            {{ $contract->purpose ?? '' }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                            @php
                                                switch ($contract->admin_status) {
                                                    case 'approved':
                                                        $statusClasses =
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                                        break;
                                                    case 'flagged':
                                                        $statusClasses =
                                                            'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
                                                        break;
                                                    case 'rejected':
                                                        $statusClasses =
                                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                                        break;
                                                    default:
                                                        $statusClasses =
                                                            'bg-yellow-200 text-gray-600 dark:bg-yellow-600 dark:text-yellow-200';
                                                        break;
                                                }
                                                $statusText = ucfirst($contract->admin_status ?? 'pending');
                                            @endphp
                                            <span
                                                class="inline-block px-2 sm:px-3 py-1 {{ $statusClasses }} rounded-full text-xs sm:text-sm font-medium">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            {{ $contract->actioned_by ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            {{ $contract->admin_notes ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                            <button data-id="{{ $contract->id }}"
                                                class="viewContract bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8"
                                            class="px-4 sm:px-6 py-12 text-center text-sm sm:text-base text-gray-500 dark:text-gray-400">
                                            No contracts uploaded yet. Start by adding a new contract!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="mt-6 flex justify-between items-center px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $contracts->firstItem() }} to {{ $contracts->lastItem() }} of
                            {{ $contracts->total() }} contracts
                        </div>
                        <div class="flex gap-2">
                            @if ($contracts->onFirstPage())
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-600 dark:text-gray-400">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $contracts->previousPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Previous
                                </a>
                            @endif

                            @foreach ($contracts->links()->elements[0] as $page => $url)
                                @if ($page == $contracts->currentPage())
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded-md dark:bg-blue-700">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($contracts->hasMorePages())
                                <a href="{{ $contracts->nextPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Next
                                </a>
                            @else
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-600 dark:text-gray-400">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">Upload Contract</h2>
                    <button id="closeModal" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                </div>
                <form method="POST" action="{{ route('contracts.store') }}" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <div class="grid grid-rows-2 gap-4">
                        <div>
                            <label for="purpose" class="block text-sm font-medium py-2">Purpose</label>
                            <input type="text" name="purpose" value="{{ old('purpose') }}" required
                                class="w-full border p-2 rounded">
                            @error('purpose')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="contract" class="block text-sm font-medium py-2">Contract File</label>
                            <input type="file" name="contract" accept=".pdf,.jpg,.png" required
                                class="w-full border p-2 rounded">
                            @error('contract')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md w-full mt-6">
                        Upload Contract
                    </button>
                </form>
                @if (session('message'))
                    <p class="mt-2 text-green-600 text-sm">{{ session('message') }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">Contract Details</h2>
                    <button id="closeViewModal" class="text-gray-600 hover:text-gray-900 text-3xl">×</button>
                </div>
                <div id="contractDetails" class="text-sm text-gray-700">
                    <table class="w-full border-collapse">
                        <tbody>
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button id="cancelView"
                        class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Upload Modal
            const openModal = document.getElementById("openModal");
            const closeModal = document.getElementById("closeModal");
            const uploadModal = document.getElementById("uploadModal");

            openModal.addEventListener("click", () => {
                uploadModal.classList.remove("hidden");
            });

            closeModal.addEventListener("click", () => {
                uploadModal.classList.add("hidden");
            });

            uploadModal.addEventListener("click", (e) => {
                if (e.target === uploadModal) {
                    uploadModal.classList.add("hidden");
                }
            });

            // View Modal
            const viewModal = document.getElementById("viewModal");
            const closeViewModal = document.getElementById("closeViewModal");
            const cancelView = document.getElementById("cancelView");
            const contractDetails = document.getElementById("contractDetails");
            let currentContractId = null;

            document.querySelectorAll(".viewContract").forEach(button => {
                button.addEventListener("click", function() {
                    currentContractId = this.getAttribute("data-id");
                    console.log("Selected Contract ID:", currentContractId); // Debug log
                    const contracts = @json($contracts->items());
                    const contract = contracts.find(c => c.id == parseInt(currentContractId));

                    if (contract) {
                        contractDetails.querySelector("tbody").innerHTML = `
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Vendor Name</td>
                                <td class="py-2 px-4">${contract.vendor.user.name}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Email</td>
                                <td class="py-2 px-4">${contract.vendor.user.email}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Date Created</td>
                                <td class="py-2 px-4">${new Date(contract.created_at).toLocaleDateString()}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Purpose</td>
                                <td class="py-2 px-4">${contract.purpose}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Status</td>
                                <td class="py-2 px-4">${contract.admin_status ? contract.admin_status.charAt(0).toUpperCase() + contract.admin_status.slice(1) : 'Pending'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Performed By</td>
                                <td class="py-2 px-4">${contract.actioned_by || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Reason</td>
                                <td class="py-2 px-4">${contract.admin_notes || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">View Submitted File</td>
                                <td class="py-2 px-4">
                                    <button class="view-file-button bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700 transition" data-id="${currentContractId}">
                                        View File
                                    </button>
                                </td>
                            </tr>
                        `;
                        viewModal.classList.remove("hidden");
                    } else {
                        console.error("Contract not found for ID:", currentContractId);
                    }
                });
            });

            // Event delegation for dynamically created "View File" buttons
            contractDetails.addEventListener("click", function(e) {
                const viewFileButton = e.target.closest(".view-file-button");
                if (viewFileButton) {
                    const contractId = viewFileButton.getAttribute("data-id");
                    if (contractId) {
                        const fileUrl = "{{ route('contracts.preview', ':id') }}".replace(':id',
                            contractId);
                        console.log("Opening file URL:", fileUrl); // Debug log
                        window.open(fileUrl, '_blank');
                    } else {
                        console.error("No contract ID available for preview.");
                    }
                }
            });

            function closeViewModalFn() {
                viewModal.classList.add("hidden");
                currentContractId = null;
            }

            closeViewModal.addEventListener("click", closeViewModalFn);
            cancelView.addEventListener("click", closeViewModalFn);
            viewModal.addEventListener("click", (e) => {
                if (e.target === viewModal) {
                    closeViewModalFn();
                }
            });
        });
    </script>
</x-app-layout>
