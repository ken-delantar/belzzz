<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bus Transportation Procurement Agency</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Sidebar -->
        @include('sidebar.sidebar')
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="flex-1 md:ml-64" style="padding-left: 20px">
            {{ $slot }}
        </div>
        <!-- End Main Content Area -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');

            if (sidebar && toggleIcon) {
                sidebar.classList.toggle('-translate-x-full');
                toggleIcon.classList.toggle('rotate-180');
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                const toggleButton = sidebar.querySelector('button');
                if (window.innerWidth < 768 &&
                    !sidebar.contains(e.target) &&
                    toggleButton && !toggleButton.contains(e.target) &&
                    !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                    const toggleIcon = document.getElementById('toggleIcon');
                    if (toggleIcon) {
                        toggleIcon.classList.remove('rotate-180');
                    }
                }
            }
        });

        // Adjust sidebar on window resize
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            if (sidebar && window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
            }
        });

        // Basic search filter (client-side demo)
        const searchInput = document.querySelector('input[type="text"]');
        const tableRows = document.querySelectorAll('tbody tr:not(#empty-state)');
        const emptyState = document.getElementById('empty-state');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                let hasVisibleRows = false;

                tableRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const isVisible = text.includes(query);
                    row.classList.toggle('hidden', !isVisible);
                    if (isVisible) hasVisibleRows = true;
                });

                if (emptyState) {
                    emptyState.classList.toggle('hidden', hasVisibleRows);
                }
            });
        }

        // Dropdown functionality with outside click to close
        const dropdowns = {
            notifications: {
                btn: 'notificationsBtn',
                dropdown: 'notificationsDropdown'
            },
            messages: {
                btn: 'messagesBtn',
                dropdown: 'messagesDropdown'
            },
            profile: {
                btn: 'profileBtn',
                dropdown: 'profileDropdown'
            }
        };

        Object.values(dropdowns).forEach(({
            btn,
            dropdown
        }) => {
            const btnElement = document.getElementById(btn);
            const dropdownElement = document.getElementById(dropdown);

            if (btnElement && dropdownElement) {
                // Toggle dropdown on button click
                btnElement.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = !dropdownElement.classList.contains('hidden');
                    Object.values(dropdowns).forEach(d => {
                        if (d.dropdown !== dropdown) {
                            const otherDropdown = document.getElementById(d.dropdown);
                            if (otherDropdown) {
                                otherDropdown.classList.add('hidden');
                            }
                        }
                    });
                    dropdownElement.classList.toggle('hidden', isOpen);
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!dropdownElement.classList.contains('hidden')) {
                        const isClickInsideDropdown = dropdownElement.contains(e.target);
                        const isClickOnButton = btnElement.contains(e.target);
                        if (!isClickInsideDropdown && !isClickOnButton) {
                            dropdownElement.classList.add('hidden');
                        }
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</body>

</html>
