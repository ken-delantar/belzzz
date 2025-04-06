<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8 md:space-y-10">

            @include('navigation.header')

            <div class="col-span-1 lg:col-span-2 bg-white rounded-2xl p-6 sm:p-8 shadow-md overflow-x-auto">
                <h2 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">
                    Notifications
                </h2>
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
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 px-3 sm:px-4 font-medium">RFP-2023-001</td>
                            <td class="py-3 px-3 sm:px-4">3 Aug, 2023</td>
                            <td class="py-3 px-3 sm:px-4">XYZ Corp</td>
                            <td class="py-3 px-3 sm:px-4">$50,000</td>
                            <td class="py-3 px-3 sm:px-4">
                                <span
                                    class="inline-block px-2 sm:px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs sm:text-sm font-medium">Open</span>
                            </td>
                        </tr>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                            <td class="py-3 px-3 sm:px-4 font-medium">RFP-2023-002</td>
                            <td class="py-3 px-3 sm:px-4">15 Sep, 2023</td>
                            <td class="py-3 px-3 sm:px-4">Acme Inc</td>
                            <td class="py-3 px-3 sm:px-4">$75,000</td>
                            <td class="py-3 px-3 sm:px-4">
                                <span
                                    class="inline-block px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-600 rounded-full text-xs sm:text-sm font-medium">Under
                                    Review</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-3 sm:px-4 font-medium">RFP-2023-003</td>
                            <td class="py-3 px-3 sm:px-4">10 Oct, 2023</td>
                            <td class="py-3 px-3 sm:px-4">Global Tech</td>
                            <td class="py-3 px-3 sm:px-4">$120,000</td>
                            <td class="py-3 px-3 sm:px-4">
                                <span
                                    class="inline-block px-2 sm:px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs sm:text-sm font-medium">Awarded</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</x-app-layout>
