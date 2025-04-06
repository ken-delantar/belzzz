<header
    class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 md:mb-10 gap-4 sm:gap-6">
    <!-- Search Bar -->
    <div class="relative w-full sm:w-64 md:w-72 lg:w-80">
        <input type="text" placeholder="Search {{ ucfirst(explode('/', request()->path())[0]) }}..."
            class="w-full pl-8 sm:pl-10 pr-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg bg-white text-sm sm:text-base text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm" />
        <svg class="w-4 h-4 sm:w-5 sm:h-5 absolute left-2 sm:left-3 top-1/2 transform -translate-y-1/2 text-gray-400"
            viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </div>
    <!-- Icons and Profile -->
    <div class="flex items-center gap-3 sm:gap-4 relative">
        <!-- Notifications Dropdown -->
        <div class="relative">
            <button id="notificationsBtn"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-label="Notifications">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <div id="notificationsDropdown"
                class="absolute right-0 mt-2 w-64 sm:w-72 bg-white rounded-lg shadow-lg z-10 hidden max-h-96 overflow-y-auto">
                <div class="p-3 sm:p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Notifications
                    </h3>
                </div>
                <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-900">New contract assigned: CON-003</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 flex-shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-900">Payment overdue for CON-001</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                </div>
                <div class="p-3 sm:p-4 border-t border-gray-200 bg-gray-50">
                    <button
                        class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium focus:outline-none focus:underline transition-colors">View
                        All Notifications</button>
                </div>
            </div>
        </div>
        <!-- Messages Dropdown -->
        <div class="relative">
            <button id="messagesBtn"
                class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
                aria-label="Messages">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <div id="messagesDropdown"
                class="absolute right-0 mt-2 w-64 sm:w-72 bg-white rounded-lg shadow-lg z-10 hidden max-h-96 overflow-y-auto">
                <div class="p-3 sm:p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-sm sm:text-base md:text-lg font-semibold text-gray-900">Messages</h3>
                </div>
                <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600 flex-shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-900">John: Contract CON-002 approved!</p>
                            <p class="text-xs text-gray-500">Yesterday</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor">
                            <path
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div>
                            <p class="text-xs sm:text-sm text-gray-900">Support: Invoice query</p>
                            <p class="text-xs text-gray-500">3 days ago</p>
                        </div>
                    </div>
                </div>
                <div class="p-3 sm:p-4 border-t border-gray-200 bg-gray-50">
                    <button
                        class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium focus:outline-none focus:underline transition-colors">View
                        All Messages</button>
                </div>
            </div>
        </div>
        <!-- Profile Dropdown -->
        <div class="relative">
            <div id="profileBtn"
                class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-900 rounded-full flex items-center justify-center text-white text-sm sm:text-base font-medium cursor-pointer hover:opacity-90 transition-opacity"
                title="User Profile">
                @if (Auth::check() && Auth::user()->role === 'Admin')
                    @php
                        $nameParts = explode(' ', trim(Auth::user()->name ?? ''));
                        $first = $nameParts[0] ?? '';
                        $last = count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : '';
                    @endphp
                    {{ substr($first, 0, 1) . substr($last, 0, 1) }}
                @else
                    {{ Auth::user()->vendor ? substr(Auth::user()->vendor->firstname ?? '', 0, 1) . substr(Auth::user()->vendor->lastname ?? '', 0, 1) : '' }}
                @endif
            </div>
            <div id="profileDropdown"
                class="absolute right-0 mt-2 w-48 sm:w-56 bg-white rounded-lg shadow-lg z-10 hidden">
                <div class="p-3 sm:p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div
                            class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-900 rounded-full flex items-center
                            justify-center text-white text-sm sm:text-base font-medium">
                            @if (Auth::check() && Auth::user()->role === 'Admin')
                                @php
                                    $nameParts = explode(' ', trim(Auth::user()->name ?? ''));
                                    $first = $nameParts[0] ?? '';
                                    $last = count($nameParts) > 1 ? $nameParts[count($nameParts) - 1] : '';
                                @endphp
                                {{ substr($first, 0, 1) . substr($last, 0, 1) }}
                            @else
                                {{ Auth::user()->vendor ? substr(Auth::user()->vendor->firstname ?? '', 0, 1) . substr(Auth::user()->vendor->lastname ?? '', 0, 1) : '' }}
                            @endif
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm md:text-base font-semibold text-gray-900">
                                {{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-2 sm:p-3 space-y-1 sm:space-y-2">
                    <button
                        class="w-full text-left px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Edit Profile
                    </button>
                    <button
                        class="w-full text-left px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm text-gray-700 hover:bg-gray-100 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Settings
                    </button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button
                            class="w-full text-left px-2 sm:px-3 py-1 sm:py-2 text-xs sm:text-sm text-red-600 hover:bg-red-50 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-red-500"
                            type="submit">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
