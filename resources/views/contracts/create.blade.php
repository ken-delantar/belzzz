<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            @include('navigation.header')

            <!-- Main Grid Content -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <form method="GET" action="{{ route('proposals.contract.custom') }}" class="mb-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label>Vendor Name</label>
                            <input type="text" name="vendor_name" class="w-full p-2 border rounded-md"
                                value="Vendor Name" required>
                        </div>
                        <div>
                            <label>Vendor Address</label>
                            <input type="text" name="vendor_address" class="w-full p-2 border rounded-md"
                                value="Vendor Address" required>
                        </div>
                        <div>
                            <label>Service Description</label>
                            <input type="text" name="service_description" class="w-full p-2 border rounded-md"
                                value="Daily passenger transport">
                        </div>
                        <div>
                            <label>Service Area</label>
                            <input type="text" name="service_area" class="w-full p-2 border rounded-md"
                                value="Metro Manila routes">
                        </div>
                        <div>
                            <label>Schedule</label>
                            <input type="text" name="schedule" class="w-full p-2 border rounded-md"
                                value="Monday to Friday, 6 AM - 6 PM">
                        </div>
                        <div>
                            <label>Vehicles</label>
                            <input type="text" name="vehicles" class="w-full p-2 border rounded-md"
                                value="2 buses, minimum 40-seat capacity each">
                        </div>
                        <div>
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="w-full p-2 border rounded-md"
                                value="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label>End Date</label>
                            <input type="date" name="end_date" class="w-full p-2 border rounded-md"
                                value="{{ now()->addYear()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label>Rate</label>
                            <input type="text" name="rate" class="w-full p-2 border rounded-md"
                                value="$500 per bus per month">
                        </div>
                    </div>
                    <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Generate Contract PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
