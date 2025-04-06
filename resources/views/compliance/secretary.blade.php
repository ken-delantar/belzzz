<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">

            @include('navigation.header')

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    Vendor Compliance Submissions
                </h1>
            </div>

            <!-- Compliance List -->
            @if ($compliances->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                        Submission ID
                                    </th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                        Vendor
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                        Date Submitted
                                    </th>
                                    {{-- <th
                                        class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                        Status
                                    </th> --}}
                                    <th
                                        class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                        Documents
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($compliances as $compliance)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                        <td class="px-6 py-4 text-gray-900 dark:text-gray-100">
                                            #{{ $compliance->id }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                            {{ $compliance->vendor->user->name ?? 'Unknown Vendor' }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                            {{ $compliance->created_at->format('F j, Y') }}
                                        </td>
                                        {{-- <td class="px-6 py-4">
                                            <span
                                                class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                                @if ($compliance->status === 'approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif ($compliance->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($compliance->status) }}
                                            </span>
                                        </td> --}}
                                        <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                            {{ $compliance->documents->count() }} file(s)
                                        </td>
                                        <td class="px-6 py-4">
                                            <button data-id="{{ $compliance->id }}"
                                                data-documents='{{ json_encode(
                                                    $compliance->documents->map(
                                                        fn($doc) => [
                                                            'file_name' => $doc->file_name,
                                                            'document_path' => $doc->document_path,
                                                        ],
                                                    ),
                                                ) }}'
                                                class="view-documents bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200">
                                                View Documents
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-lg text-gray-700 dark:text-gray-300">
                        No vendor compliance submissions yet.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Documents Modal -->
    <div id="documentsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div id="modal-content"
            class="bg-white rounded-lg p-6 shadow-lg max-w-md w-full transform transition-transform scale-95 dark:bg-gray-800">
            <h2 id="modal-title" class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">Submission
                Documents</h2>
            <div id="modal-documents" class="mt-4">
                <h3 class="text-md font-medium text-gray-700 mb-2 dark:text-gray-300">Uploaded Documents:</h3>
                <ul id="modal-document-list" class="space-y-2 text-gray-700 dark:text-gray-300"></ul>
            </div>
            <div class="mt-6 flex justify-end">
                <button id="close-modal-btn"
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700 transition duration-200 dark:bg-gray-600 dark:hover:bg-gray-500">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- JavaScript for Modal -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('documentsModal');
            const modalTitle = document.getElementById('modal-title');
            const modalDocumentList = document.getElementById('modal-document-list');
            const modalContent = document.querySelector('#documentsModal #modal-content');
            const closeModalBtn = document.getElementById('close-modal-btn');

            // Open Modal on Button Click
            document.querySelectorAll('.view-documents').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const documents = JSON.parse(button.getAttribute('data-documents'));

                    modalTitle.textContent = `Submission ID#${id}`;
                    modalDocumentList.innerHTML = documents.length > 0 ?
                        documents.map(doc => `
                            <li class="mb-2">
                                <p><strong>File:</strong> ${doc.file_name}</p>
                                <a href="/storage/${doc.document_path}" target="_blank" class="text-blue-500 underline">View Document</a>
                            </li>
                        `).join('') :
                        '<li>No documents available.</li>';

                    modal.classList.remove('hidden');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                });
            });

            // Close Modal
            function closeModal() {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }

            closeModalBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        });
    </script>
</x-app-layout>
