<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')

            <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row gap-6 sm:gap-8 p-6 sm:p-8">
                <div class="flex-1 w-full">
                    <!-- Tabs with improved colors -->
                    <div class="border-b flex flex-wrap gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-6">
                        <button
                            class="tab-button active px-3 py-2 text-sm sm:text-base font-medium focus:outline-none text-indigo-600 border-b-2 border-indigo-600"
                            data-tab="account">
                            Account Settings
                        </button>
                        <button
                            class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-600 hover:text-indigo-500 focus:outline-none"
                            data-tab="company">
                            Company Settings
                        </button>
                        <button
                            class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-600 hover:text-indigo-500 focus:outline-none"
                            data-tab="documents">
                            Documents
                        </button>
                    </div>

                    <!-- Tab Contents (only account is active by default) -->
                    <div class="tab-content active bg-white p-1 shadow rounded-lg" id="account">
                        <div id="profile-info">
                            <div class="space-y-4">
                                <div class="flex flex-col">
                                    <label class="text-sm font-medium text-gray-600">Full Name:</label>
                                    <p class="text-lg text-gray-900 font-semibold">
                                        {{ Auth::user()->name }}
                                    </p>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-sm font-medium text-gray-600">Address:</label>
                                    <p class="text-lg text-gray-900 font-semibold">
                                        {{ $vendor->address }}
                                    </p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-600">Email:</label>
                                        <p class="text-lg text-gray-900 font-semibold">
                                            {{ Auth::user()->email }}
                                        </p>
                                    </div>
                                    <div class="flex flex-col">
                                        <label class="text-sm font-medium text-gray-600">Contact Number:</label>
                                        <p class="text-lg text-gray-900 font-semibold">
                                            {{ $vendor->contact_info }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="profile-update-form" class="hidden mt-0">
                            @include('profile.partials.update-profile-information-form', [
                                'user' => Auth::user(),
                            ])
                        </div>
                    </div>

                    <!-- Company Tab (hidden by default) -->
                    <div class="tab-content hidden bg-white p-1 shadow rounded-lg" id="company">
                        <div id="company">
                            <p>Company</p>
                        </div>
                    </div>

                    <!-- Documents Tab (hidden by default) -->
                    <div class="tab-content hidden bg-white p-1 shadow rounded-lg" id="documents">
                        <div id="documents">
                            <p>Documents</p>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-64 lg:w-72 flex flex-col md:border-l md:pl-6 lg:pl-8">
                    <div class="profile-card flex flex-col items-center h-full">
                        <div class="relative flex flex-col items-center">
                            <form action="{{ route('profile.update_profile') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!-- Hidden File Input -->
                                <input type="file" id="profile-image-input" name="profile_photo" accept="image/*"
                                    class="hidden" onchange="previewProfileImage(event)">


                                <!-- Profile Image (Click to Upload) -->
                                <label for="profile-image-input" class="cursor-pointer relative group">
                                    <img id="profile-image-preview"
                                        src="{{ asset('storage/uploads/' . Auth::user()->vendor->profile_photo ?? 'default-avatar.png') }}"
                                        alt="Profile"
                                        class="w-24 h-24 rounded-full object-cover shadow-md border-2 border-gray-300 group-hover:border-indigo-500 transition-all">

                                    <!-- Overlay Effect -->
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="text-white text-xs font-semibold">Click to Upload</span>
                                    </div>
                                </label>

                                <!-- Upload Button -->
                                <button id="upload-button" type="SVGNumberList"
                                    class="mt-3 px-4 py-2 bg-indigo-600 text-white text-sm rounded-md shadow-md hover:bg-indigo-700 transition hidden"
                                    onclick="uploadProfileImage()">
                                    Upload Image
                                </button>
                            </form>
                        </div>

                        <!-- JavaScript for Preview & Upload -->
                        <script>
                            function previewProfileImage(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        document.getElementById('profile-image-preview').src = e.target.result;
                                        document.getElementById('upload-button').classList.remove('hidden'); // Show Upload Button
                                    };
                                    reader.readAsDataURL(file);
                                }
                            }
                        </script>

                        <h2 class="mt-4 text-xl font-semibold text-gray-800 text-center">{{ Auth::user()->name }}</h2>
                        <p class="text-sm text-gray-500 text-center">Bus Terminal Owner</p>
                        <div class="flex-grow"></div>
                        <div class="w-full px-4">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Centered Modal -->
    <x-modal name="cancel-modal" :show="false" maxWidth="sm" class="flex items-center justify-center min-h-screen">
        <div class="p-6 bg-white rounded-lg shadow-xl">
            <h2 class="text-lg font-semibold text-gray-800">Cancel Update?</h2>
            <p class="mt-2 text-sm text-gray-600">
                Any unsaved changes will be lost. Are you sure you want to cancel?
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button x-on:click="show = false" id="keep-editing"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 hover:text-gray-900 transition-colors duration-150">
                    No, Keep Editing
                </button>
                <button id="confirm-cancel" x-on:click="confirmCancel()"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors duration-150">
                    Yes, Cancel
                </button>
            </div>
        </div>
    </x-modal>

    <!-- Updated Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.tab-button').forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault(); // Prevent default button behavior

                    // Update tab button styles
                    document.querySelectorAll('.tab-button').forEach(t => {
                        t.classList.remove('active', 'text-indigo-600', 'border-b-2',
                            'border-indigo-600');
                        t.classList.add('text-gray-600', 'hover:text-indigo-500');
                    });
                    tab.classList.add('active', 'text-indigo-600', 'border-b-2',
                        'border-indigo-600');
                    tab.classList.remove('text-gray-600', 'hover:text-indigo-500');

                    // Switch tab content
                    const tabId = tab.getAttribute('data-tab');
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                        content.classList.add('hidden'); // Ensure inactive tabs are hidden
                    });
                    const activeTab = document.getElementById(tabId);
                    activeTab.classList.remove('hidden');
                    activeTab.classList.add('active');
                });
            });
        });

        function toggleProfileUpdate() {
            const profileInfo = document.getElementById('profile-info');
            const profileForm = document.getElementById('profile-update-form');
            const updateButton = document.getElementById('toggle-profile-update');

            if (!profileForm.classList.contains('hidden')) {
                window.dispatchEvent(new CustomEvent('open-modal', {
                    detail: 'cancel-modal'
                }));
            } else {
                profileForm.classList.remove('hidden');
                profileInfo.classList.add('hidden');
                updateButton.textContent = 'Cancel Update';
                updateButton.classList.remove('bg-indigo-600', 'text-white');
                updateButton.classList.add('border', 'border-red-600', 'text-red-600', 'bg-transparent',
                    'hover:bg-red-600', 'hover:text-white');
            }
        }

        function confirmCancel() {
            const profileInfo = document.getElementById('profile-info');
            const profileForm = document.getElementById('profile-update-form');
            const updateButton = document.getElementById('toggle-profile-update');

            profileForm.classList.add('hidden');
            profileInfo.classList.remove('hidden');
            updateButton.textContent = 'Update Profile';
            updateButton.classList.remove('border', 'border-red-600', 'text-red-600', 'bg-transparent',
                'hover:bg-red-600', 'hover:text-white');
            updateButton.classList.add('bg-indigo-600', 'text-white');

            window.dispatchEvent(new CustomEvent('close-modal', {
                detail: 'cancel-modal'
            }));
        }

        document.addEventListener('DOMContentLoaded', function() {
            const updateButton = document.getElementById('toggle-profile-update');
            if (updateButton) {
                updateButton.addEventListener('click', toggleProfileUpdate);
            }
        });
    </script>
</x-app-layout>
