{{-- <x-app-layout>
    <div
        class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen flex flex-col">
        <!-- Header -->
        @include('navigation.header')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col space-y-8 overflow-hidden">
            <!-- Create Bid Card -->
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

            <!-- Submitted Bids Section -->
            <div
                class="max-w-[80%] bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-all duration-300 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Submitted Vendor Bids</h2>

                @if ($proposals->isNotEmpty())
                    <div class="flex-1 overflow-hidden">
                        <div class="max-w-full overflow-x-auto">
                            <div class="max-h-[50vh] overflow-y-auto">
                                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300">
                                    <thead
                                        class="text-xs uppercase bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 sticky top-0 z-10">
                                        <tr>
                                            <th scope="col" class="px-4 py-3" style="width: 5%;">Rank</th>
                                            <th scope="col" class="px-4 py-3" style="width: 15%;">Title</th>
                                            <th scope="col" class="px-4 py-3" style="width: 12%;">Vendor</th>
                                            <th scope="col" class="px-4 py-3" style="width: 15%;">Email</th>
                                            <th scope="col" class="px-4 py-3" style="width: 8%;">Type</th>
                                            <th scope="col" class="px-4 py-3" style="width: 10%;">Pricing</th>
                                            <th scope="col" class="px-4 py-3" style="width: 12%;">Timeline</th>
                                            <th scope="col" class="px-4 py-3" style="width: 10%;">Valid Until</th>
                                            <th scope="col" class="px-4 py-3" style="width: 8%;">AI Score</th>
                                            <th scope="col" class="px-4 py-3" style="width: 8%;">Status</th>
                                            <th scope="col" class="px-4 py-3 text-center" style="width: 5%;">Actions
                                            </th>
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
                                                <td class="px-4 py-3">{{ $proposal->proposal_title ?? 'Untitled' }}</td>
                                                <td class="px-4 py-3">
                                                    {{ $proposal->vendor_name ?? $proposal->user->name }}</td>
                                                <td class="px-4 py-3">{{ $proposal->email ?? $proposal->user->email }}
                                                </td>
                                                <td class="px-4 py-3">
                                                    {{ ucfirst($proposal->product_service_type ?? 'N/A') }}</td>
                                                <td class="px-4 py-3">{{ $proposal->pricing ?? 'N/A' }}</td>
                                                <td class="px-4 py-3">{{ $proposal->delivery_timeline ?? 'N/A' }}</td>
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
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto flex flex-col"
                role="dialog" aria-labelledby="modal-title" aria-modal="true">
                <div class="flex justify-between items-center p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="modal-title" class="text-xl font-semibold text-gray-900 dark:text-gray-100">Submit a New Bid
                    </h2>
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
                <form id="bidForm" class="p-6 space-y-6" method="POST" action="{{ route('proposals.store') }}"
                    aria-label="Bid Submission Form">
                    @csrf
                    <!-- Form fields remain unchanged -->
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50" role="dialog"
            aria-labelledby="delete-title" aria-modal="true">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                <h2 id="delete-title" class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm
                    Deletion</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete this bid?</p>
                <div class="flex justify-end gap-2">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded">Cancel</button>
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(`dropdown-${id}`);
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                dropdown.querySelector('button').focus();
            }
        }

        function openDeleteModal(id) {
            const modal = document.getElementById('delete-modal');
            const form = document.getElementById('delete-form');
            form.action = `/proposals/${id}`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('proposalModal');
            const openModalBtn = document.getElementById('openProposalModal');
            const closeModalBtn = document.getElementById('closeProposalModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const form = document.getElementById('bidForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            // Open modal
            openModalBtn.addEventListener('click', () => {
                modal.classList.remove('opacity-0', 'pointer-events-none');
                modal.classList.add('opacity-100', 'pointer-events-auto');
            });

            // Close modal
            function closeProposalModal() {
                modal.classList.remove('opacity-100', 'pointer-events-auto');
                modal.classList.add('opacity-0', 'pointer-events-none');
            }
            closeModalBtn.addEventListener('click', closeProposalModal);
            cancelBtn.addEventListener('click', closeProposalModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeProposalModal();
            });

            // Form submission
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
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                    });
                    const result = await response.json();

                    if (response.ok) {
                        closeProposalModal();
                        setTimeout(() => location.reload(), 500);
                        alert(result.message || 'Bid submitted successfully!');
                    } else {
                        throw new Error(result.error || 'Unknown server error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Submit Bid';
                    loadingSpinner.classList.add('hidden');
                }
            });

            // Date validation
            const validUntilInput = document.getElementById('valid_until');
            const today = new Date().toISOString().split('T')[0];
            validUntilInput.setAttribute('min', today);
            validUntilInput.addEventListener('change', function() {
                if (this.value < today) {
                    this.value = today;
                    alert('Valid until date cannot be in the past.');
                }
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', (e) => {
                document.querySelectorAll('[id^="dropdown-"]').forEach(dropdown => {
                    if (!dropdown.contains(e.target) && !e.target.closest(
                            'button[onclick^="toggleDropdown"]')) {
                        dropdown.classList.add('hidden');
                    }
                });
            });
        });
    </script>

    <!-- Custom Scrollbar Styles -->
    <style>
        .max-h-[50vh]::-webkit-scrollbar {
            width: 8px;
        }

        .max-h-[50vh]::-webkit-scrollbar-thumb {
            background-color: #6b7280;
            border-radius: 4px;
        }

        .max-h-[50vh]::-webkit-scrollbar-track {
            background-color: #e5e7eb;
        }

        .dark .max-h-[50vh]::-webkit-scrollbar-thumb {
            background-color: #9ca3af;
        }

        .dark .max-h-[50vh]::-webkit-scrollbar-track {
            background-color: #374151;
        }

        /* Ensure table cells don't wrap unnecessarily */
        th,
        td {
            white-space: nowrap;
        }
    </style>
</x-app-layout> --}}
