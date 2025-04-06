<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">User Management</h3>
                <div class="mt-4 sm:mt-0 flex gap-4">
                    <a href="{{ route('usermanagement.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 flex items-center">
                        <i class="fas fa-user-plus mr-2"></i> Add New User
                    </a>
                </div>
            </div>

            <!-- Users Table -->
            <div class="mb-10">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Registered Users</h2>
                <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
                    <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Name</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Email</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Role</th>
                                <th
                                    class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                                    <td class="px-4 py-4 sm:px-6 text-gray-900 dark:text-gray-100 truncate max-w-xs">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                        {{ ucfirst($user->role === 'Staff' ? 'Secretary' : $user->role) }}
                                    </td>
                                    <td class="px-4 py-4 sm:px-6 flex gap-2">
                                        <a href="{{ route('usermanagement.edit', $user->id) }}"
                                            class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                                            Edit
                                        </a>
                                        <button type="button" data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            class="open-delete-modal bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <x-modal name="deleteUserModal" :show="false" focusable maxWidth="md"
                class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
                <div class="p-6 bg-white rounded-lg dark:bg-gray-800 w-full max-w-lg shadow-xl">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Confirm Deletion</h2>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">
                        Are you sure you want to delete <span id="delete-user-name" class="font-medium"></span>?
                        This action cannot be undone.
                    </p>
                    <form id="delete-user-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end gap-3">
                            <button type="button" id="close-delete-modal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600">
                                Delete
                            </button>
                        </div>
                    </form>
                </div>
            </x-modal>
        </div>
    </div>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.open-delete-modal');
            const deleteModal = document.querySelector('#deleteUserModal');
            const deleteForm = document.querySelector('#delete-user-form');
            const deleteUserName = document.querySelector('#delete-user-name');
            const closeDeleteModal = document.querySelector('#close-delete-modal');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const userName = this.getAttribute('data-user-name');
                    const deleteUrl = '{{ route('usermanagement.destroy', ':id') }}'.replace(':id',
                        userId);

                    // Update modal content
                    deleteUserName.textContent = userName;
                    deleteForm.action = deleteUrl;

                    // Open modal
                    window.dispatchEvent(new CustomEvent('open-modal', {
                        detail: 'deleteUserModal'
                    }));
                });
            });

            if (closeDeleteModal) {
                closeDeleteModal.addEventListener('click', function() {
                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: 'deleteUserModal'
                    }));
                });
            }
        });
    </script>
</x-app-layout>
