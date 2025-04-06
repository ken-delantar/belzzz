<div id="sidebar"
    class="fixed inset-y-0 left-0 w-64 bg-[#2196F3] text-white transform -translate-x-full md:translate-x-0 transition-all duration-300 z-50 flex flex-col h-full font-sans md:w-72">

    <!-- Toggle Button -->
    <button onclick="toggleSidebar()"
        class="absolute -right-3 top-4 bg-white rounded-full shadow-md h-6 w-6 flex items-center justify-center text-gray-500 md:hidden">
        <svg id="toggleIcon" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <!-- Sidebar Header -->
    <div class="p-4 flex gap-2 items-center border-b border-blue-700">
        <img src="{{ asset('assets/images/logo.png') }}" alt="" width="50px" height="auto" style="border-radius: 50%; padding: 2px; background-color: white">
        <span class="font-semibold text-lg tracking-tight md:text-xl">
            {{ Auth::check()
                ? (Auth::user()->role === 'Admin'
                    ? 'ADMIN'
                    : (Auth::user()->role === 'Staff'
                        ? 'STAFF'
                        : (Auth::user()->role === 'Driver'
                            ? 'DRIVER'
                            : (Auth::user()->role === 'Secretary'
                                ? 'SECRETARY'
                                : 'VENDOR'))))
                : 'VENDOR' }}            
            PORTAL
        </span>
    </div>

    <!-- Scrollable Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 space-y-1">
        <!-- Shared Dashboard Link -->
        @if (Auth::user()->role !== 'Driver' && Auth::user()->role !== 'Staff')
            <x-nav-item href="{{ route('dashboard') }}" active="{{ Route::currentRouteName() === 'dashboard' }}"
                icon="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                Dashboard
            </x-nav-item>
        @endif

        <!-- Admin Routes -->
        @if (Auth::user()->role === 'Admin')
            {{-- <x-nav-item href="{{ route('marketplace.admin.store') }}"
                active="{{ Route::currentRouteName() === 'marketplace.admin.store' }}"
                icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                Procurement Portal
            </x-nav-item> --}}
            <x-nav-item href="{{ route('vehicles.index') }}"
                active="{{ Route::currentRouteName() === 'vehicles.index' }}"
                icon="M9 17V7m0 10h6m-6 0a2 2 0 110-4 2 2 0 010 4zm6 0a2 2 0 110-4 2 2 0 010 4zM5 7h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z">
                Vehicle Inventory
            </x-nav-item>
            <x-nav-item href="{{ route('proposals.admin') }}"
                active="{{ Route::currentRouteName() === 'proposals.admin' }}"
                icon="M7 21h10a2 2 0 002-2V9l-5-5H7a2 2 0 00-2 2v13a2 2 0 002 2zM13 4v5h5">
                Proposal Management
            </x-nav-item>
            <x-nav-item href="{{ route('maintenance.index') }}"
                active="{{ Route::currentRouteName() === 'maintenance.index' }}"
                icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                Maintenance Management
            </x-nav-item>
            {{-- <x-nav-item href="{{ route('reports.vendor') }}"
                active="{{ Route::currentRouteName() === 'reports.vendor' }}"
                icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                Write a Review
            </x-nav-item> --}}
        @elseif(Auth::user()->role == 'Secretary')
            <x-nav-item href="{{ route('marketplace.admin.store') }}"
                active="{{ Route::currentRouteName() === 'marketplace.admin.store' }}"
                icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                Procurement Portal
            </x-nav-item>
            <x-nav-item href="{{ route('vehicles.index') }}"
                active="{{ Route::currentRouteName() === 'vehicles.index' }}"
                icon="M9 17V7m0 10h6m-6 0a2 2 0 110-4 2 2 0 010 4zm6 0a2 2 0 110-4 2 2 0 010 4zM5 7h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z">
                Vehicle Inventory
            </x-nav-item>
            <x-nav-item href="{{ route('maintenance.index') }}"
                active="{{ Route::currentRouteName() === 'maintenance.index' }}"
                icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                Maintenance Management
            </x-nav-item>
            <!-- Vendor Routes -->
        @elseif (Auth::user()->role === 'Vendor')
            <x-nav-item href="{{ route('marketplace.vendor.index') }}"
                active="{{ Route::currentRouteName() === 'marketplace.vendor.index' }}"
                icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                Procurement Portal
            </x-nav-item>
            <x-nav-item href="{{ route('proposals.index') }}"
                active="{{ Route::currentRouteName() === 'proposals.index' || Route::currentRouteName() === 'proposals.approved' }}"
                icon="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                Proposal Submission
            </x-nav-item>
            <x-nav-item href="{{ route('compliance.index') }}"
                active="{{ Route::currentRouteName() === 'compliance.index' }}"
                icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                Compliance & Training
            </x-nav-item>
            <x-nav-item href="{{ route('reports.index') }}"
                active="{{ Route::currentRouteName() === 'reports.index' }}"
                icon="M3 3h18v18H3z M7 10h10M7 14h7M7 6h10">
                Feedbacks
            </x-nav-item>
            <div class="mt-4 px-4 py-3 text-white font-semibold text-sm md:text-base">
                Account Settings
            </div>
            <x-nav-item href="{{ route('profile.show', Auth::user()->vendor) }}"
                active="{{ Route::currentRouteName() === 'profile.show' }}"
                icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                Profile Information
            </x-nav-item>
        @elseif (Auth::user()->role === 'Driver')
            <x-nav-item href="{{ route('maintenance.index') }}"
                active="{{ Route::currentRouteName() === 'maintenance.index' }}"
                icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                Maintenance Management
            </x-nav-item>
        @elseif (Auth::user()->role === 'Staff')
            <x-nav-item href="{{ route('marketplace.admin.store') }}"
                active="{{ Route::currentRouteName() === 'marketplace.admin.store' }}"
                icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                Procurement Portal
            </x-nav-item>
            <x-nav-item href="{{ route('marketplace.admin.staff_requests') }}"
                active="{{ Route::currentRouteName() === 'marketplace.admin.staff_requests' }}"
                icon="M21 12h-2m-2.64 5.36l-1.42-1.42M12 21v-2m-5.36-2.64l1.42-1.42M3 12h2m2.64-5.36l1.42 1.42M12 3v2m5.36 2.64l-1.42 1.42 M12 12m-4 0a4 4 0 018 0">
                Procurement Requests
            </x-nav-item>
            <x-nav-item href="{{ route('maintenance.index') }}"
                active="{{ Route::currentRouteName() === 'maintenance.index' }}"
                icon="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                Maintenance Management
            </x-nav-item>

            <div class="mt-4 px-4 py-3 text-white font-semibold text-sm md:text-base">
                Compliance & Files
            </div>

            <x-nav-item href="{{ route('compliance.secretary') }}"
                active="{{ Route::currentRouteName() === 'compliance.secretary' }}"
                icon="M9 2a1 1 0 00-1 1v1H5a2 2 0 00-2 2v11a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2h-3V3a1 1 0 00-1-1H9zm0 2h2v1H9V4zm1 4h4v1h-4V8zm0 2h4v1h-4v-1zm0 2h4v1h-4v-1z">
                Compliance Documents
            </x-nav-item>
        @endif

        @if (Auth::user()->role !== 'Driver' && Auth::user()->role !== 'Secretary' && Auth::user()->role !== 'Staff')
            <x-nav-item href="{{ route('billings.index') }}"
                active="{{ Route::currentRouteName() === 'billings.index' }}"
                icon="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                Billing and Invoicing
            </x-nav-item>
        @endif

        <!-- Notifications Section -->
        {{-- <div class="mt-4 px-4 py-3 text-white font-semibold text-sm md:text-base">
            Notifications & Reminders
        </div>
        <x-nav-item href="{{ route('notifications.index') }}"
            active="{{ Route::currentRouteName() === 'notifications.index' }}"
            icon="M21 12h-2m-2.64 5.36l-1.42-1.42M12 21v-2m-5.36-2.64l1.42-1.42M3 12h2m2.64-5.36l1.42 1.42M12 3v2m5.36 2.64l-1.42 1.42 M12 12m-4 0a4 4 0 018 0">
            Notifications
        </x-nav-item> --}}

        @if (Auth::user()->role === 'Admin')
            <div class="mt-4 px-4 py-3 text-white font-semibold text-sm md:text-base">
                Account Settings
            </div>
            <x-nav-item href="{{ route('usermanagement.index') }}"
                active="{{ Route::currentRouteName() === 'usermanagement.index' }}"
                icon="M21 12h-2m-2.64 5.36l-1.42-1.42M12 21v-2m-5.36-2.64l1.42-1.42M3 12h2m2.64-5.36l1.42 1.42M12 3v2m5.36 2.64l-1.42 1.42 M12 12m-4 0a4 4 0 018 0">
                User Management
            </x-nav-item>
        @endif
    </nav>
</div>
