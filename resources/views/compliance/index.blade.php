<x-app-layout>
    <div class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-gray-50 min-h-screen" id="main_container">
        <!-- Header -->
        @include('navigation.header')

        <!-- Content Box -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg mt-12 sm:mt-16">
            <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Compliance and Training</h1>
                    <p class="text-sm text-gray-600 mt-1">Upload all necessary files to process your proposal!</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 sm:px-6 py-6">
                <!-- Summary and Audio Section -->
                <div id="submission-list" class="space-y-4"></div>

                <!-- Upload Section (wider) -->
                <div class="md:col-span-2">
                    <form id="uploadForm" enctype="multipart/form-data">
                        @csrf
                        <label for="file-upload" id="drop-area"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition">
                            <div id="drop-text" class="text-center">
                                <p class="text-sm text-gray-600"><strong>Click to upload</strong> or drag and drop</p>
                                <p class="text-xs text-gray-500 mt-2">PDF (Max. 10 files, 10MB each)</p>
                            </div>
                            <input id="file-upload" type="file" name="files[]" class="hidden" accept=".pdf"
                                multiple />
                        </label>
                        <div class="mt-4">
                            <button type="submit" id="upload-button"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition disabled:opacity-50"
                                disabled>Upload</button>
                        </div>
                    </form>
                </div>

                <!-- Pagination Controls (Aligned Right) -->
                <div id="pagination-controls" class="flex justify-end gap-4 mt-4">
                    <button id="prev-page"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50"
                        disabled>Previous</button>
                    <span id="page-info" class="text-sm text-gray-600 self-center"></span>
                    <button id="next-page"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50"
                        disabled>Next</button>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <p class="text-xs sm:text-sm text-gray-500">
                    Compliance Checklist: Safety inspections, driver qualifications, etc.
                </p>
            </div>
        </div>

        <!-- Card Modal (Static Example) -->
        <div id="cardModal1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl transform transition-all duration-300 scale-100"
                    id="modal-content">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Task Details</h3>
                    <p class="mb-2 text-sm sm:text-base text-gray-700"><strong>Vehicle:</strong> Truck #1234</p>
                    <p class="mb-2 text-sm sm:text-base text-gray-700"><strong>Task:</strong> Oil Change</p>
                    <p class="mb-4 text-sm sm:text-base text-gray-700"><strong>Date:</strong> 2024-03-25</p>
                    <div class="flex justify-end">
                        <button onclick="closeCardModal('cardModal1')"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Modal -->
        <div id="cardModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div id="modal-content"
                class="bg-white rounded-lg p-6 shadow-lg transform transition-transform scale-95 max-w-md w-full">
                <h2 id="modal-title" class="text-lg font-semibold text-gray-800 mb-4">Submission Details</h2>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
                <div id="modal-documents" class="mt-4">
                    <h3 class="text-md font-medium text-gray-700 mb-2">Uploaded Documents:</h3>
                    <ul id="modal-document-list" class="space-y-2"></ul>
                </div>
                <div class="mt-6 flex justify-end">
                    <button id="close-modal-btn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-700">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <!-- Loader Modal -->
        <div id="loader-modal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-60 pointer-events-auto">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="mt-2 text-white text-base font-medium">Uploading...</p>
            </div>
        </div>
        <!-- Success Modal -->
        <div id="success-modal" class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
            <div class="flex justify-center items-center min-h-screen p-4">
                <div class="relative w-full max-w-md mx-4">
                    <div class="relative bg-white rounded-lg shadow-md">
                        <div class="p-4 sm:p-6 space-y-3 sm:space-y-4 text-center">
                            <p class="text-gray-700 text-sm sm:text-base">Files Uploaded Successfully!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // DOM Elements
            const elements = {
                dropArea: document.getElementById('drop-area'),
                fileInput: document.getElementById('file-upload'),
                dropText: document.getElementById('drop-text'),
                uploadForm: document.getElementById('uploadForm'),
                uploadButton: document.getElementById('upload-button'),
                submissionList: document.getElementById('submission-list'),
                modal: document.getElementById('cardModal'),
                modalTitle: document.getElementById('modal-title'),
                modalStatus: document.getElementById('modal-status'),
                modalDocumentList: document.getElementById('modal-document-list'),
                modalContent: document.querySelector('#cardModal #modal-content'),
                closeModalBtn: document.getElementById('close-modal-btn'),
                prevPageBtn: document.getElementById('prev-page'),
                nextPageBtn: document.getElementById('next-page'),
                pageInfo: document.getElementById('page-info'),
                loaderModal: document.getElementById('loader-modal')
            };

            console.log('DOM Loaded, elements:', elements);

            // Pagination State
            let currentPage = 1;
            const cardsPerPage = 4;
            let allSubmissions = [];

            // Initialize Functionality
            initDragAndDrop();
            initFileUpload();
            initCardClickListener();
            fetchSubmissions();

            // Drag and Drop Handling
            function initDragAndDrop() {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    elements.dropArea.addEventListener(eventName, preventDefaults, false);
                });

                elements.dropArea.addEventListener('dragover', () => elements.dropArea.classList.add(
                    'border-blue-500', 'bg-blue-50'));
                elements.dropArea.addEventListener('dragleave', () => elements.dropArea.classList.remove(
                    'border-blue-500', 'bg-blue-50'));
                elements.dropArea.addEventListener('drop', (e) => {
                    elements.fileInput.files = e.dataTransfer.files;
                    updateDropText(elements.fileInput.files);
                    updateButtonState(); // Update button state on drop
                });
                elements.fileInput.addEventListener('change', () => {
                    updateDropText(elements.fileInput.files);
                    updateButtonState(); // Update button state on file input change
                });
            }

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function updateDropText(files) {
                elements.dropText.innerHTML = files.length > 0 ?
                    `<p class="text-sm text-gray-600">Selected: ${files.length} file(s)</p>` : "";
            }

            function updateButtonState() {
                elements.uploadButton.disabled = elements.fileInput.files.length === 0;
            }

            // Inside initFileUpload function, update the success block
            function initFileUpload() {
                elements.uploadForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const formData = new FormData(elements.uploadForm);

                    // Show loader
                    elements.loaderModal.classList.remove('hidden');

                    fetch("{{ route('vendor_compliance.upload') }}", {
                            method: "POST",
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            elements.loaderModal.classList.add('hidden'); // Hide loader
                            if (data.success) {
                                // Show success modal
                                const successModal = document.getElementById('success-modal');
                                successModal.classList.remove('hidden');

                                // Close modal and refresh page after 3 seconds
                                setTimeout(() => {
                                    successModal.classList.add('hidden');
                                    window.location.reload(); // Refresh the page
                                }, 1000); // 3000ms = 3 seconds
                            } else {
                                console.error("Upload Error:", data.error);
                            }
                        })
                        .catch(error => {
                            elements.loaderModal.classList.add('hidden'); // Hide loader on error
                            console.error("Fetch Error:", error);
                        });
                });
            }

            // Fetch Submissions with Pagination
            function fetchSubmissions() {
                fetch("{{ route('vendor_compliance.list') }}")
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched data:', data);
                        if (!Array.isArray(data)) {
                            console.error('Data is not an array:', data);
                            return;
                        }

                        // Deduplicate by vendor_compliance_id
                        const groupedSubmissions = {};
                        data.forEach(compliance => {
                            if (!groupedSubmissions[compliance.id]) {
                                groupedSubmissions[compliance.id] = {
                                    id: compliance.id,
                                    status: compliance.status,
                                    documents: []
                                };
                            }
                            groupedSubmissions[compliance.id].documents.push(...compliance.documents);
                        });

                        allSubmissions = Object.values(groupedSubmissions);
                        renderPage(currentPage);
                    })
                    .catch(error => console.error("Error fetching submissions:", error));
            }

            function renderPage(page) {
                const start = (page - 1) * cardsPerPage;
                const end = start + cardsPerPage;
                const paginatedSubmissions = allSubmissions.slice(start, end);

                elements.submissionList.innerHTML = paginatedSubmissions.map(compliance => `
                    <div class="submission-card flex items-center justify-between bg-white p-4 rounded-lg shadow-md cursor-pointer hover:shadow-xl hover:scale-105 transition-transform duration-300"
                        data-id="${compliance.id}"
                        data-status="${compliance.status}"
                        data-documents='${JSON.stringify(compliance.documents)}'>
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            <h3 class="font-medium text-gray-800">Submission ID#${compliance.id}</h3>
                        </div>
                        <div class="flex items-center gap-2">
                            ${getStatusLabel(compliance.status)}
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                `).join("");

                updatePaginationControls(page);
            }

            function getStatusLabel(status) {
                const statusMap = {
                    "pending": {
                        text: "Pending",
                        color: "yellow"
                    },
                    "approved": {
                        text: "Approved",
                        color: "green"
                    },
                    "expired": {
                        text: "Expired",
                        color: "red"
                    }
                };
                return status in statusMap ?
                    `<span class="bg-${statusMap[status].color}-100 text-${statusMap[status].color}-800 px-2 py-1 rounded-md text-xs font-medium">${statusMap[status].text}</span>` :
                    "";
            }

            // Pagination Controls
            function updatePaginationControls(page) {
                const totalPages = Math.ceil(allSubmissions.length / cardsPerPage);
                elements.pageInfo.textContent = `Page ${page} of ${totalPages}`;

                elements.prevPageBtn.disabled = page === 1;
                elements.nextPageBtn.disabled = page === totalPages || totalPages === 0;

                elements.prevPageBtn.onclick = () => {
                    if (page > 1) {
                        currentPage--;
                        renderPage(currentPage);
                    }
                };

                elements.nextPageBtn.onclick = () => {
                    if (page < totalPages) {
                        currentPage++;
                        renderPage(currentPage);
                    }
                };
            }

            // Card Click Listener
            function initCardClickListener() {
                elements.submissionList.addEventListener('click', (e) => {
                    const card = e.target.closest('.submission-card');
                    if (!card) return;

                    const id = card.getAttribute('data-id');
                    const status = card.getAttribute('data-status');
                    const documentsJson = card.getAttribute('data-documents');

                    console.log('Card clicked:', {
                        id,
                        status,
                        documentsJson
                    });
                    openCardModal(id, status, documentsJson);
                });

                elements.closeModalBtn.addEventListener('click', closeCardModal);
            }

            // Inside the openCardModal function
            function openCardModal(id, status, documentsJson) {
                console.log('openCardModal called with:', {
                    id,
                    status,
                    documentsJson
                });

                try {
                    const documents = JSON.parse(documentsJson);
                    console.log('Parsed documents:', documents);

                    if (!elements.modal || !elements.modalTitle || !elements.modalStatus || !elements
                        .modalDocumentList) {
                        console.error('Modal elements missing:', {
                            modal: elements.modal,
                            modalTitle: elements.modalTitle,
                            modalStatus: elements.modalStatus,
                            modalDocumentList: elements.modalDocumentList
                        });
                        return;
                    }

                    elements.modalTitle.textContent = `Submission ID#${id}`;
                    elements.modalStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                    elements.modalDocumentList.innerHTML = documents.map(doc => `
            <li class="mb-2">
                <p><strong>File:</strong> ${doc.file_name}</p>
                <a href="/storage/${doc.document_path}" target="_blank" class="text-blue-500 underline">View Document</a>
            </li>
        `).join('');

                    elements.modal.classList.remove('hidden');
                    elements.modalContent.classList.remove('scale-95');
                    elements.modalContent.classList.add('scale-100');
                    console.log('Modal should now be visible');
                } catch (error) {
                    console.error('Error in openCardModal:', error);
                }
            }

            function closeCardModal() {
                if (!elements.modal) return;

                elements.modalContent.classList.remove('scale-100');
                elements.modalContent.classList.add('scale-95');
                setTimeout(() => {
                    elements.modal.classList.add('hidden');
                    console.log('Modal closed');
                }, 300);
            }

            // Static Modal Close (unchanged)
            window.closeCardModal = function(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.querySelector('#modal-content').classList.remove('scale-100');
                    setTimeout(() => modal.classList.add('hidden'), 300);
                }
            };
        });
    </script>
</x-app-layout>
