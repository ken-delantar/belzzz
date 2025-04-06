<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 bg-gray-50 min-h-screen">
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        @include('navigation.header')

        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 sm:gap-8">
            <!-- Center Column: Upcoming Maintenance -->
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Upcoming Maintenance</h2>
                    @if (Auth::user()->role === 'Driver')
                        <button onclick="openModal()"
                            class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm sm:text-base font-semibold">
                            <span class="text-lg sm:text-xl">+</span> Schedule Task
                        </button>
                    @endif
                </div>

                <div class="space-y-4 sm:space-y-6">
                    @forelse ($maintenances as $maintenance)
                        <div onclick="openCardModal({{ $maintenance->id }})"
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300 cursor-pointer">
                            <div class="flex items-center gap-4 sm:gap-6">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 sm:h-7 sm:w-7 text-gray-400 flex-shrink-0" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                                </svg>
                                <div>
                                    <h3
                                        class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                                        Bus #{{ $maintenance->vehicle->vehicle_number }}
                                        @if ($maintenance->is_priority)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Priority
                                            </span>
                                        @endif
                                    </h3>
                                    <p class="text-gray-500 text-sm sm:text-base">{{ $maintenance->description }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3 mt-3 sm:mt-0">
                                <span class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-md text-xs sm:text-sm">
                                    {{ $maintenance->maintenance_date->format('Y-m-d') }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm sm:text-base">No upcoming maintenance tasks found.</p>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $maintenances->links() }}
                </div>
            </div>

            <!-- Right Sidebar: Maintenance Alerts & Quick Stats -->
            <div class="w-full lg:w-80 space-y-6 sm:space-y-8">
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-red-500 flex-shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Maintenance Alerts
                    </h2>
                    <div class="space-y-3 sm:space-y-4">
                        @foreach ($overdueTasks as $task)
                            <div onclick="openCardModal({{ $task->id }})"
                                class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4 cursor-pointer hover:bg-red-100 transition">
                                <h3 class="text-red-800 font-semibold text-sm sm:text-base">Overdue:
                                    {{ $task->description }}</h3>
                                <p class="text-red-600 text-xs sm:text-sm">
                                    Bus #{{ $task->vehicle->vehicle_number }} -
                                    {{ $task->maintenance_date->diffForHumans() }} overdue
                                </p>
                            </div>
                        @endforeach
                        @foreach ($dueSoonTasks as $task)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                                <h3 class="text-yellow-800 font-semibold text-sm sm:text-base">Due Soon:
                                    {{ $task->description }}</h3>
                                <p class="text-yellow-600 text-xs sm:text-sm">
                                    Bus #{{ $task->vehicle->vehicle_number }} - Due in
                                    {{ $task->maintenance_date->diffInDays(now()) }} days
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Quick Stats</h2>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex justify-between items-center cursor-pointer hover:bg-gray-100 p-2 rounded-md transition"
                            onclick="openPendingTasksModal()">
                            <span class="text-gray-600 text-sm sm:text-base">Pending Tasks</span>
                            <span
                                class="font-semibold text-gray-900 text-sm sm:text-base">{{ $pendingTasksCount }}</span>
                        </div>
                        <div class="flex justify-between items-center cursor-pointer hover:bg-gray-100 p-2 rounded-md transition"
                            onclick="openCompletedThisMonthModal()">
                            <span class="text-gray-600 text-sm sm:text-base">Completed This Month</span>
                            <span
                                class="font-semibold text-gray-900 text-sm sm:text-base">{{ $completedThisMonthCount }}</span>
                        </div>
                        <div class="flex justify-between items-center cursor-pointer hover:bg-gray-100 p-2 rounded-md transition"
                            onclick="openActiveVehiclesModal()">
                            <span class="text-gray-600 text-sm sm:text-base">Active Vehicles</span>
                            <span
                                class="font-semibold text-gray-900 text-sm sm:text-base">{{ $activeVehiclesCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Schedule Task Modal -->
    <div id="scheduleModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-md mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 rounded-t">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Schedule Task</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-7 h-7 inline-flex justify-center items-center"
                            onclick="closeModal()">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-3 sm:p-4">
                        <form id="scheduleModalForm" method="POST" action="{{ route('maintenance.store') }}">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div class="relative">
                                    <label for="vehicle-search"
                                        class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                                    <input type="hidden" id="vehicle-id" name="vehicle_id" required>
                                    <div id="vehicle-dropdown"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm cursor-pointer bg-white flex items-center justify-between"
                                        onclick="toggleVehicleDropdown()">
                                        <span id="selected-vehicle" class="text-gray-500">Select Vehicle</span>
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                    <div id="vehicle-options"
                                        class="hidden absolute w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 z-10 max-h-60 overflow-y-auto">
                                        <div class="p-2 border-b border-gray-200">
                                            <input type="text" id="vehicle-search"
                                                class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500"
                                                placeholder="Search vehicles..." onkeyup="filterVehicles()">
                                        </div>
                                        <div id="vehicle-list">
                                            @foreach ($vehicles as $vehicle)
                                                <div class="vehicle-option px-3 py-2 hover:bg-gray-100 cursor-pointer"
                                                    data-id="{{ $vehicle->id }}"
                                                    data-text="Bus #{{ $vehicle->vehicle_number }}"
                                                    onclick="selectVehicle('{{ $vehicle->id }}', 'Bus #{{ $vehicle->vehicle_number }}')">
                                                    <p class="text-sm font-medium text-gray-900">Bus</p>
                                                    <p class="text-xs text-gray-500">#{{ $vehicle->vehicle_number }}
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="task-desc"
                                        class="block text-sm font-medium text-gray-700 mb-1">Task</label>
                                    <input type="text" id="task-desc" name="task_desc"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="Task Description" required>
                                </div>
                                <div>
                                    <label for="task-date"
                                        class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" id="task-date" name="task_date"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                        required>
                                </div>
                                <div>
                                    <label for="assigned-tech"
                                        class="block text-sm font-medium text-gray-700 mb-1">Technician</label>
                                    <select id="assigned-tech" name="assigned_tech"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm">
                                        <option value="" selected>Select Technician</option>
                                        @foreach ($vendorsOptions as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->firstname }}
                                                {{ $vendor->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" id="priority-yes" name="priority" value="1"
                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <label for="priority-yes" class="ml-2 text-sm text-gray-700">Yes</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="priority-no" name="priority" value="0"
                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                checked>
                                            <label for="priority-no" class="ml-2 text-sm text-gray-700">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label for="estimated-cost"
                                        class="block text-sm font-medium text-gray-700 mb-1">Estimated Cost</label>
                                    <input type="number" id="estimated-cost" name="estimated_cost"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm"
                                        placeholder="₱0.00" step="0.01" required>
                                </div>
                                <div class="sm:col-span-2">
                                    <label for="notes"
                                        class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea id="notes" name="notes"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 text-sm" rows="2"
                                        placeholder="Any extra details..."></textarea>
                                </div>
                            </div>
                            <div class="mt-4 sm:mt-6 flex justify-end space-x-2">
                                <button type="button"
                                    class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-4 py-2 transition"
                                    onclick="closeModal()">Cancel</button>
                                <button type="submit"
                                    class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 py-2 transition">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance Details Modal -->
    <div id="maintenanceModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-md mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Task Details</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeCardModal()">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="space-y-2 sm:space-y-3">
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Vehicle:</strong> <span
                                    id="modalVehicle"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Number:</strong> <span
                                    id="modalVehicleNumber"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Task:</strong> <span
                                    id="modalTask"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Date:</strong> <span
                                    id="modalDate"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Cost:</strong> <span
                                    id="modalCost"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Priority:</strong> <span
                                    id="modalPriority"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Status:</strong> <span
                                    id="modalStatus"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Created By:</strong> <span
                                    id="modalCreatedBy"></span></p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Technician:</strong> <span
                                    id="modalTechnician"></span></p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCardModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Modals -->
    <div id="pendingTasksModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-lg mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Pending Tasks</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closePendingTasksModal()">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4 max-h-96 overflow-y-auto">
                        <p class="text-gray-700 text-sm sm:text-base"><strong>Total Pending Tasks:</strong> <span
                                class="font-semibold">{{ $pendingTasksCount }}</span></p>
                        @forelse ($pendingTasksList as $task)
                            <div class="border-b border-gray-200 py-2">
                                <p class="text-gray-600 text-sm">Bus #{{ $task->vehicle->vehicle_number }} -
                                    {{ $task->description }}</p>
                                <p class="text-gray-500 text-xs">Due: {{ $task->maintenance_date->format('Y-m-d') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No pending tasks.</p>
                        @endforelse
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closePendingTasksModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="completedThisMonthModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-lg mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Completed This Month</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeCompletedThisMonthModal()">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4 max-h-96 overflow-y-auto">
                        <p class="text-gray-700 text-sm sm:text-base"><strong>Tasks Completed This Month:</strong>
                            <span class="font-semibold">{{ $completedThisMonthCount }}</span>
                        </p>
                        @forelse ($completedThisMonthList as $task)
                            <div class="border-b border-gray-200 py-2">
                                <p class="text-gray-600 text-sm">Bus #{{ $task->vehicle->vehicle_number }} -
                                    {{ $task->description }}</p>
                                <p class="text-gray-500 text-xs">Completed:
                                    {{ $task->maintenance_date->format('Y-m-d') }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No tasks completed this month.</p>
                        @endforelse
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCompletedThisMonthModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="activeVehiclesModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-lg mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Active Vehicles</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeActiveVehiclesModal()">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4 max-h-96 overflow-y-auto">
                        <p class="text-gray-700 text-sm sm:text-base"><strong>Total Active Vehicles:</strong> <span
                                class="font-semibold">{{ $activeVehiclesCount }}</span></p>
                        @forelse ($activeVehiclesList as $vehicle)
                            <div class="border-b border-gray-200 py-2">
                                <p class="text-gray-600 text-sm">Bus #{{ $vehicle->vehicle_number }}</p>
                                <p class="text-gray-500 text-xs">Status: {{ $vehicle->status }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm">No active vehicles.</p>
                        @endforelse
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeActiveVehiclesModal()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleVehicleDropdown() {
            const options = document.getElementById('vehicle-options');
            options.classList.toggle('hidden');
        }

        function selectVehicle(id, text) {
            document.getElementById('vehicle-id').value = id;
            document.getElementById('selected-vehicle').textContent = text;
            document.getElementById('vehicle-options').classList.add('hidden');
            document.getElementById('vehicle-search').value = '';
            filterVehicles();
        }

        function filterVehicles() {
            const searchInput = document.getElementById('vehicle-search').value.toLowerCase();
            const options = document.getElementsByClassName('vehicle-option');
            Array.from(options).forEach(option => {
                const text = option.getAttribute('data-text').toLowerCase();
                option.style.display = text.includes(searchInput) ? '' : 'none';
            });
        }

        window.addEventListener('click', (e) => {
            const dropdown = document.getElementById('vehicle-dropdown');
            const options = document.getElementById('vehicle-options');
            if (!dropdown.contains(e.target) && !options.contains(e.target)) {
                options.classList.add('hidden');
            }
        });

        function openModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        function openCardModal(id) {
            fetch("{{ route('maintenance.show', '') }}/" + id, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(maintenance => {
                    document.getElementById('modalVehicle').textContent = `Bus #${maintenance.vehicle.vehicle_number}`;
                    document.getElementById('modalVehicleNumber').textContent = maintenance.vehicle.vehicle_number;
                    document.getElementById('modalTask').textContent = maintenance.description || 'N/A';
                    document.getElementById('modalDate').textContent = maintenance.maintenance_date;
                    document.getElementById('modalCost').textContent = maintenance.cost ?
                        `₱${Number(maintenance.cost).toLocaleString()}` : 'N/A';
                    document.getElementById('modalPriority').textContent = maintenance.is_priority ? 'Yes' : 'No';
                    document.getElementById('modalStatus').textContent = maintenance.status;
                    document.getElementById('modalCreatedBy').textContent = maintenance.created_by?.name ?? 'N/A';
                    document.getElementById('modalTechnician').textContent = maintenance.assigned_technician
                        ?.firstname && maintenance.assigned_technician?.lastname ?
                        `${maintenance.assigned_technician.firstname} ${maintenance.assigned_technician.lastname}` :
                        'N/A';
                    document.getElementById('maintenanceModal').classList.remove('hidden');
                })
                .catch(error => console.error('Error fetching maintenance:', error));
        }

        function closeCardModal() {
            document.getElementById('maintenanceModal').classList.add('hidden');
        }

        function openPendingTasksModal() {
            document.getElementById('pendingTasksModal').classList.remove('hidden');
        }

        function closePendingTasksModal() {
            document.getElementById('pendingTasksModal').classList.add('hidden');
        }

        function openCompletedThisMonthModal() {
            document.getElementById('completedThisMonthModal').classList.remove('hidden');
        }

        function closeCompletedThisMonthModal() {
            document.getElementById('completedThisMonthModal').classList.add('hidden');
        }

        function openActiveVehiclesModal() {
            document.getElementById('activeVehiclesModal').classList.remove('hidden');
        }

        function closeActiveVehiclesModal() {
            document.getElementById('activeVehiclesModal').classList.add('hidden');
        }

        window.addEventListener('click', (e) => {
            const modals = [{
                    id: 'scheduleModal',
                    close: closeModal
                },
                {
                    id: 'maintenanceModal',
                    close: closeCardModal
                },
                {
                    id: 'pendingTasksModal',
                    close: closePendingTasksModal
                },
                {
                    id: 'completedThisMonthModal',
                    close: closeCompletedThisMonthModal
                },
                {
                    id: 'activeVehiclesModal',
                    close: closeActiveVehiclesModal
                }
            ];
            modals.forEach(modal => {
                const element = document.getElementById(modal.id);
                if (element && !element.classList.contains('hidden')) {
                    const modalContent = element.querySelector('.relative');
                    if (e.target === element && !modalContent.contains(e.target)) {
                        modal.close();
                    }
                }
            });
        });
    </script>
</x-app-layout>
