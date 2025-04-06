{{-- <div id="sidebar"
    class="fixed inset-y-0 left-0 w-20 sm:w-64 md:w-72 lg:w-80 bg-white border-r shadow-md transition-all duration-300 z-20">
    <!-- Toggle Button -->
    <button onclick="toggleSidebar()"
        class="absolute -right-3 top-4 sm:top-6 bg-white rounded-full shadow-md h-6 w-6 sm:h-8 sm:w-8 flex items-center justify-center z-10 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <svg id="toggleIcon" class="w-4 h-4 sm:w-5 sm:h-5 transform transition-transform" fill="none"
            stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
        </svg>
    </button>
    <!-- Sidebar Content -->
    <div class="overflow-hidden h-full flex flex-col">
        <!-- Sidebar Header -->
        <div class="p-3 sm:p-4 flex items-center gap-2 sm:gap-3 border-b border-gray-200">
            <span
                class="text-sm sm:text-base md:text-lg lg:text-xl font-semibold text-gray-900 whitespace-nowrap">VENDOR
                PORTAL</span>
        </div>
        <!-- Navigation Section -->
        <nav class="py-2 sm:py-4 space-y-1 sm:space-y-2 flex-1 overflow-y-auto">
            <a href="vendor_dashboard.html">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 bg-blue-900 text-white cursor-pointer hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Dashboard</span>
                </div>
            </a>
            <a href="vendor_contract.html">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 text-blue-900 cursor-pointer hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Contract</span>
                </div>
            </a>
            <a href="vendor_profile.html">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 text-blue-900 cursor-pointer hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="12" cy="7" r="4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Profile</span>
                </div>
            </a>
            <a href="vendor_request.html">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 text-blue-900 cursor-pointer hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Proposal Submission</span>
                </div>
            </a>
            <a href="vendor_compliance.html">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 text-blue-900 cursor-pointer hover:bg-blue-100 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Compliance & Training</span>
                </div>
            </a>
            <a href="vendor_report.html">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 bg-blue-900 text-white cursor-pointer hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Write a Review</span>
                </div>
            </a>
        </nav>
        <!-- Additional Links -->
        <div
            class="mt-2 sm:mt-4 px-3 sm:px-4 py-2 text-gray-700 text-xs sm:text-sm md:text-base font-medium border-t border-gray-200">
            Notifications & Reminders
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleIcon = document.getElementById('toggleIcon');

        if (sidebar.classList.contains('w-20')) {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('sm:w-64', 'md:w-72', 'lg:w-80');
            toggleIcon.classList.add('rotate-180');
        } else {
            sidebar.classList.remove('sm:w-64', 'md:w-72', 'lg:w-80');
            sidebar.classList.add('w-20');
            toggleIcon.classList.remove('rotate-180');
        }
    }
</script> --}}
