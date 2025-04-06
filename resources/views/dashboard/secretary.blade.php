<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 bg-gray-50 min-h-screen">

        @include('navigation.header')

        <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8 md:space-y-10">
            <!-- Stats Grid -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 bg-white rounded-2xl p-6 sm:p-8 shadow-md">
                <!-- Registered Vendors -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-500"></div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm sm:text-base font-medium">Registered Vendors</p>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">
                                {{ $registeredVendorsCount }}</h3>
                            <span
                                class="{{ $vendorChange >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs sm:text-sm font-semibold">
                                {{ $vendorChange >= 0 ? '↑' : '↓' }} {{ abs($vendorChange) }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Active RFPs -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-orange-100 flex items-center justify-center flex-shrink-0">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-orange-500"></div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm sm:text-base font-medium">Active RFPs</p>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">{{ $activeRfpsCount }}
                            </h3>
                            <span class="text-green-500 text-xs sm:text-sm font-semibold">{{ $newRfpsCount }} New</span>
                        </div>
                    </div>
                </div>
                <!-- Proposals Submitted -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-green-500"></div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm sm:text-base font-medium">Proposals Submitted</p>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">
                                {{ $proposalsSubmittedCount }}</h3>
                            <span
                                class="{{ $proposalsChange >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs sm:text-sm font-semibold">
                                {{ $proposalsChange >= 0 ? '↑' : '↓' }} {{ abs($proposalsChange) }}
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Contracts Awarded -->
                <div class="flex items-center gap-4 sm:gap-6">
                    <div
                        class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-pink-100 flex items-center justify-center flex-shrink-0">
                        <div class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-pink-500"></div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm sm:text-base font-medium">Contracts Awarded</p>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <h3 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">
                                {{ $contractsAwardedCount }}</h3>
                            <span
                                class="{{ $contractsChange >= 0 ? 'text-green-500' : 'text-red-500' }} text-xs sm:text-sm font-semibold">
                                {{ $contractsChange >= 0 ? '↑' : '↓' }} {{ abs($contractsChange) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart & Registration Card Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Chart Section: Monthly Vendor Registrations -->
                <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8 shadow-md">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Monthly Vendor
                        Registrations</h2>
                    <div class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">
                        {{ array_sum($monthlyRegistrations) }} Registrations
                    </div>
                    <!-- Bar Chart -->
                    <div class="flex items-end h-40 sm:h-48 md:h-64 gap-2 sm:gap-4 md:gap-6 overflow-x-auto">
                        @php
                            $months = [
                                'Mar',
                                'Apr',
                                'May',
                                'Jun',
                                'Jul',
                                'Aug',
                                'Sep',
                                'Oct',
                                'Nov',
                                'Dec',
                                'Jan',
                                'Feb',
                            ];
                            $maxHeight = max($monthlyRegistrations ?: [1]); // Avoid division by zero
                        @endphp
                        @foreach ($months as $month)
                            <div
                                class="flex flex-col items-center gap-2 sm:gap-3 flex-1 min-w-[2.5rem] sm:min-w-[3rem] relative group">
                                @if (isset($monthlyRegistrations[$month]))
                                    <div
                                        class="absolute -top-8 sm:-top-10 md:-top-12 bg-gray-900 text-white px-2 py-1 rounded text-xs hidden group-hover:block">
                                        {{ $monthlyRegistrations[$month] }}
                                    </div>
                                    <div class="w-full bg-blue-500 rounded-t-lg hover:bg-blue-600 transition"
                                        style="height: {{ ($monthlyRegistrations[$month] / $maxHeight) * 100 }}%;">
                                    </div>
                                @else
                                    <div
                                        class="w-full bg-gray-100 rounded-t-lg h-20 sm:h-24 md:h-28 hover:bg-gray-200 transition">
                                    </div>
                                @endif
                                <span class="text-xs sm:text-sm text-gray-600">{{ $month }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <!-- Registration Call-to-Action Card -->
                <div class="bg-blue-600 rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden shadow-md">
                    <div
                        class="absolute bottom-0 right-0 w-48 sm:w-64 h-48 sm:h-64 bg-blue-500 rounded-full transform translate-x-1/2 translate-y-1/2 opacity-50">
                    </div>
                    <span
                        class="inline-block px-3 py-1 bg-white text-blue-600 text-xs sm:text-sm font-semibold rounded-full mb-4 sm:mb-6 shadow">NEW</span>
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-semibold mb-4 sm:mb-6 relative z-10">Vendor
                        Registration Open!</h2>
                    <p class="text-blue-100 text-sm sm:text-base mb-6 sm:mb-8 relative z-10 leading-relaxed">
                        Register your company now to gain access to exclusive RFPs and bidding opportunities.
                    </p>
                    <a href="{{ route('vendors.create') }}"
                        class="bg-white text-blue-600 px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors relative z-10">
                        Register Now
                    </a>
                </div>
            </div>

            <!-- Bottom Grid: Recent Activities & Active RFPs -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Recent Vendor Activities -->
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-md">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Recent Vendor
                        Activities</h2>
                    <div class="space-y-6 sm:space-y-8">
                        @forelse ($recentActivities as $activity)
                            <div class="flex items-start gap-4 sm:gap-6">
                                @if (!isset($activity['proposal_id']) || !$activity['proposal_id'])
                                    <img src="/placeholder.svg"
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0"
                                        alt="Vendor avatar" />
                                @else
                                    <div
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-100 flex items-center justify-center text-orange-500 font-medium text-base sm:text-lg flex-shrink-0">
                                        {{ strtoupper(substr($activity['vendor_name'] ?? 'Proposal', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm sm:text-base text-gray-700">
                                        @if (!isset($activity['proposal_id']) || !$activity['proposal_id'])
                                            <span
                                                class="font-semibold text-gray-900">{{ $activity['vendor_name'] ?? 'Unknown' }}</span>
                                            {{ $activity['action'] }}.
                                        @else
                                            Proposal <span
                                                class="font-semibold text-gray-900">{{ $activity['proposal_id'] }}</span>
                                            {{ $activity['action'] }} by <span
                                                class="font-semibold text-gray-900">{{ $activity['vendor_name'] ?? 'Unknown' }}</span>.
                                        @endif
                                    </p>
                                    <span class="text-xs sm:text-sm text-gray-500">{{ $activity['time'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm sm:text-base text-gray-500">No recent activities.</p>
                        @endforelse
                    </div>
                </div>
                <!-- Active RFPs & Proposals Table -->
                <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8 shadow-md overflow-x-auto">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Active RFPs &
                        Proposals</h2>
                    <table class="w-full text-left text-sm sm:text-base">
                        <thead>
                            <tr class="text-gray-600 font-semibold border-b border-gray-200">
                                <th class="py-3 px-3 sm:px-4">RFP ID</th>
                                <th class="py-3 px-3 sm:px-4">Deadline</th>
                                <th class="py-3 px-3 sm:px-4">Company</th>
                                <th class="py-3 px-3 sm:px-4">Budget</th>
                                <th class="py-3 px-3 sm:px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @forelse ($activeProposals as $proposal)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="py-3 px-3 sm:px-4 font-medium">{{ $proposal->id }}</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        {{ $proposal->valid_until?->format('d M, Y') ?? 'N/A' }}</td>
                                    <td class="py-3 px-3 sm:px-4">{{ $proposal->vendor_name ?? 'Unknown' }}</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        {{ $proposal->pricing ? '$' . number_format($proposal->pricing) : 'N/A' }}</td>
                                    <td class="py-3 px-3 sm:px-4">
                                        <span
                                            class="inline-block px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium
                                            @if ($proposal->admin_status === 'pending') bg-green-100 text-green-600
                                            @elseif ($proposal->admin_status === 'under_review') bg-yellow-100 text-yellow-600
                                            @elseif ($proposal->admin_status === 'approved') bg-blue-100 text-blue-600
                                            @else bg-red-100 text-red-600 @endif">
                                            {{ ucfirst($proposal->admin_status ?? 'Pending') }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-3 px-3 sm:px-4 text-gray-500">No active RFPs or
                                        proposals.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
