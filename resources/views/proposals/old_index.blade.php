<x-app-layout>
    <div
        class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex flex-col">
        <!-- Header -->
        @include('navigation.header')

        <!-- Main Content (Fixed Height) -->
        <div class="flex-1 flex flex-col space-y-8 overflow-hidden">
            <!-- Create Bid Card (Fixed) -->
            <div
                class="w-full bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Submit a New Bid</h2>
                    <button id="openProposalModal"
                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-none transition-all dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700">
                        Add Bid
                    </button>
                </div>
                <p class="text-gray-600 dark:text-gray-300">Submit a competitive bid for a service or product.</p>
            </div>

            <!-- Submitted Bids Section (Fixed Height with Scrollable Table) -->
            <div
                class="w-full bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300 flex-1 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Submitted Vendor Bids</h2>
                @if ($proposals->isNotEmpty())
                    <div class="flex-1 overflow-hidden">
                        <div class="relative w-full overflow-x-auto">
                            <div class="max-h-[50vh] overflow-y-auto">
                                <table
                                    class="w-full table-fixed text-sm text-left text-gray-600 dark:text-gray-300 border-collapse">
                                    <thead
                                        class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 w-[5%]">Rank</th>
                                            <th scope="col" class="px-4 py-3 w-[15%]">Title</th>
                                            <th scope="col" class="px-4 py-3 w-[12%]">Vendor</th>
                                            <th scope="col" class="px-4 py-3 w-[15%]">Email</th>
                                            <th scope="col" class="px-4 py-3 w-[8%]">Type</th>
                                            <th scope="col" class="px-4 py-3 w-[10%]">Pricing</th>
                                            <th scope="col" class="px-4 py-3 w-[12%]">Timeline</th>
                                            <th scope="col" class="px-4 py-3 w-[10%]">Valid Until</th>
                                            <th scope="col" class="px-4 py-3 w-[8%]">AI Score</th>
                                            <th scope="col" class="px-4 py-3 w-[8%]">Status</th>
                                            <th scope="col" class="px-4 py-3 w-[5%] text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($proposals as $index => $proposal)
                                            <tr
                                                class="border-b dark:border-gray-600 {{ $index === 0 ? 'bg-green-50 dark:bg-green-900' : 'hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                                <td
                                                    class="px-4 py-3 font-medium {{ $index === 0 ? 'text-green-700 dark:text-green-300' : '' }}">
                                                    {{ $index + 1 }}{{ $index === 0 ? ' (Best)' : '' }}
                                                </td>
                                                <td class="px-4 py-3 truncate">
                                                    {{ $proposal->proposal_title ?? 'Untitled' }}</td>
                                                <td class="px-4 py-3 truncate">
                                                    {{ $proposal->vendor_name ?? $proposal->user->name }}</td>
                                                <td class="px-4 py-3 truncate">
                                                    {{ $proposal->email ?? $proposal->user->email }}</td>
                                                <td class="px-4 py-3">
                                                    {{ ucfirst($proposal->product_service_type ?? 'N/A') }}</td>
                                                <td class="px-4 py-3">{{ $proposal->pricing ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 truncate">
                                                    {{ $proposal->delivery_timeline ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">
                                                    {{ $proposal->valid_until ? \Carbon\Carbon::parse($proposal->valid_until)->format('Y-m-d') : 'N/A' }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    <span
                                                        class="px-2 py-1 bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200 rounded-full text-xs font-medium">
                                                        {{ $proposal->ai_score ?? 'N/A' }}/100
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3">
                                                    @php
                                                        $statusClasses = match ($proposal->admin_status) {
                                                            'approved'
                                                                => 'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200',
                                                            'rejected'
                                                                => 'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200',
                                                            default
                                                                => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-700 dark:text-yellow-200',
                                                        };
                                                        $statusText = ucfirst($proposal->admin_status ?? 'pending');
                                                    @endphp
                                                    <span
                                                        class="px-2 py-1 {{ $statusClasses }} rounded-full text-xs font-medium">
                                                        {{ $statusText }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 text-center relative">
                                                    <div class="relative">
                                                        <button aria-label="More actions"
                                                            onclick="toggleDropdown({{ $proposal->id }})"
                                                            class="p-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                                                            ⋮
                                                        </button>
                                                        <div id="dropdown-{{ $proposal->id }}"
                                                            class="absolute right-0 w-32 bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden z-20">
                                                            <a href="{{ route('proposals.preview', $proposal->id) }}"
                                                                target="_blank"
                                                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">View</a>
                                                            <a href="{{ route('proposals.edit', $proposal->id) }}"
                                                                class="block px-4 py-2 text-sm text-green-600 dark:text-green-400 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                                            <button onclick="openDeleteModal('{{ $proposal->id }}')"
                                                                class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 w-full text-left">Delete</button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Pagination -->
                    @if ($proposals->hasPages())
                        <div
                            class="mt-6 flex justify-between items-center text-sm text-gray-600 dark:text-gray-400 shrink-0">
                            <div>Showing {{ $proposals->firstItem() }} to {{ $proposals->lastItem() }} of
                                {{ $proposals->total() }} bids</div>
                            <div class="flex gap-2">{{ $proposals->links('pagination::tailwind') }}</div>
                        </div>
                    @endif
                @else
                    <div class="flex-1 text-center text-gray-500 dark:text-gray-400 py-8">
                        <p class="mt-2">No vendor bids submitted yet.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bid Submission Modal -->
        <div id="proposalModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-opacity duration-300 ease-in-out">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto flex flex-col">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Submit a New Bid</h2>
                    <button id="closeProposalModal"
                        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-300 rounded-full w-8 h-8 flex items-center justify-center"
                        aria-label="Close modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <!-- Form Body -->
                <form id="bidForm" class="p-6 space-y-6" method="POST" action="{{ route('proposals.store') }}"
                    aria-label="Bid Submission Form">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Vendor Name -->
                        <div>
                            <label for="vendor_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Vendor Name <span class="text-red-500" aria-hidden="true">*</span>
                                <span class="sr-only">(Required)</span>
                            </label>
                            <input type="text" name="vendor_name" id="vendor_name" value="{{ old('vendor_name') }}"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Enter vendor name" required aria-required="true">
                            @error('vendor_name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Email <span class="text-red-500" aria-hidden="true">*</span>
                                <span class="sr-only">(Required)</span>
                            </label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', auth()->user()->email) }}"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Enter email" required aria-required="true">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Proposal Title -->
                        <div>
                            <label for="proposal_title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Bid Title <span class="text-red-500" aria-hidden="true">*</span>
                                <span class="sr-only">(Required)</span>
                            </label>
                            <input type="text" name="proposal_title" id="proposal_title"
                                value="{{ old('proposal_title') }}"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="e.g., Bus Transport Service Bid" required aria-required="true">
                            @error('proposal_title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Product/Service Type -->
                        <div>
                            <label for="product_service_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Type
                            </label>
                            <select name="product_service_type" id="product_service_type"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                <option value="service"
                                    {{ old('product_service_type') === 'service' ? 'selected' : '' }}>Service</option>
                                <option value="product"
                                    {{ old('product_service_type') === 'product' ? 'selected' : '' }}>Product</option>
                                <option value="both" {{ old('product_service_type') === 'both' ? 'selected' : '' }}>
                                    Both</option>
                            </select>
                            @error('product_service_type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Description -->
                        <div class="col-span-1 sm:col-span-2">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="Describe your bid (e.g., Daily bus transport with 40-seat buses)">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Delivery Timeline -->
                        <div>
                            <label for="delivery_timeline"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Delivery Timeline
                            </label>
                            <input type="text" name="delivery_timeline" id="delivery_timeline"
                                value="{{ old('delivery_timeline') }}"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="e.g., Starting March 15, 2025">
                            @error('delivery_timeline')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Pricing -->
                        <div>
                            <label for="pricing" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Pricing
                            </label>
                            <input type="text" name="pricing" id="pricing" value="{{ old('pricing') }}"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                                placeholder="e.g., $500 per month">
                            @error('pricing')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <!-- Valid Until -->
                        <div class="col-span-1 sm:col-span-2">
                            <label for="valid_until"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Valid Until
                            </label>
                            <input type="date" name="valid_until" id="valid_until"
                                value="{{ old('valid_until') }}"
                                class="mt-1 block w-full rounded-md border py-2.5 px-3 text-gray-900 dark:text-gray-200 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                            @error('valid_until')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" role="alert">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="reset" id="cancelBtn"
                            class="px-4 py-2 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out flex items-center">
                            <span id="submitText">Submit Bid</span>
                            <svg id="loadingSpinner" class="hidden w-5 h-5 ml-2 animate-spin" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8v-8H4z" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm Deletion</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete this bid?</p>
                <div class="flex justify-end gap-2">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded hover:bg-gray-400 dark:hover:bg-gray-500">Cancel</button>
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const proposalModal = document.getElementById('proposalModal');
            const openModalBtn = document.getElementById('openProposalModal');
            const closeModalBtn = document.getElementById('closeProposalModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const form = document.getElementById('bidForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Open Proposal Modal
            openModalBtn.addEventListener('click', () => {
                proposalModal.classList.remove('opacity-0', 'pointer-events-none');
                proposalModal.classList.add('opacity-100', 'pointer-events-auto');
            });

            // Close Proposal Modal
            function closeProposalModal() {
                proposalModal.classList.remove('opacity-100', 'pointer-events-auto');
                proposalModal.classList.add('opacity-0', 'pointer-events-none');
            }
            closeModalBtn.addEventListener('click', closeProposalModal);
            cancelBtn.addEventListener('click', closeProposalModal);
            proposalModal.addEventListener('click', (e) => {
                if (e.target === proposalModal) closeProposalModal();
            });

            // Form Submission
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                loadingSpinner.classList.remove('hidden');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'Accept': 'application/json'
                        },
                    });
                    const result = await response.json();

                    if (response.ok) {
                        closeProposalModal();
                        setTimeout(() => location.reload(), 500);
                        alert(result.message);
                    } else {
                        console.error('Submission failed:', result.error);
                        alert('Error: ' + (result.error || 'Unknown server error'));
                    }
                } catch (error) {
                    console.error('Network error:', error);
                    alert('Network error: ' + error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Submit Bid';
                    loadingSpinner.classList.add('hidden');
                }
            });

            // Date Validation
            const validUntilInput = document.getElementById('valid_until');
            const today = new Date().toISOString().split('T')[0];
            validUntilInput.setAttribute('min', today);
            validUntilInput.addEventListener('change', function() {
                if (this.value < today) {
                    this.value = today;
                    alert('Valid until date cannot be in the past.');
                }
            });

            // Dropdown and Delete Modal Functions
            window.toggleDropdown = function(id) {
                const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
                dropdowns.forEach(dropdown => {
                    if (dropdown.id !== `dropdown-${id}`) dropdown.classList.add('hidden');
                });
                document.getElementById(`dropdown-${id}`).classList.toggle('hidden');
            };

            window.openDeleteModal = function(id) {
                const modal = document.getElementById('delete-modal');
                const form = document.getElementById('delete-form');
                form.action = `{{ url('proposals') }}/${id}`;
                modal.classList.remove('hidden');
            };

            window.closeDeleteModal = function() {
                document.getElementById('delete-modal').classList.add('hidden');
            };

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
                if (!e.target.closest('.relative')) {
                    dropdowns.forEach(dropdown => dropdown.classList.add('hidden'));
                }
            });
        });
    </script>

    <!-- Custom Styles -->
    <style>
        tbody::-webkit-scrollbar {
            width: 8px;
        }

        tbody::-webkit-scrollbar-thumb {
            background-color: #6b7280;
            /* Gray-500 */
            border-radius: 4px;
        }

        tbody::-webkit-scrollbar-track {
            background-color: #e5e7eb;
            /* Gray-200 */
        }

        .dark tbody::-webkit-scrollbar-thumb {
            background-color: #9ca3af;
            /* Gray-400 */
        }

        .dark tbody::-webkit-scrollbar-track {
            background-color: #374151;
            /* Gray-700 */
        }
    </style>
</x-app-layout>
