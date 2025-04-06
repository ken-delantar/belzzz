<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold">My Purchase Orders</h2>

        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">PO Number</th>
                    <th class="border p-2">Amount</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchaseOrders as $po)
                    <tr>
                        <td class="border p-2">{{ $po->po_number }}</td>
                        <td class="border p-2">${{ $po->amount }}</td>
                        <td class="border p-2">{{ $po->status }}</td>
                        <td class="border p-2">
                            @if ($po->status === 'Pending')
                                <form action="{{ route('purchase_orders.updateStatus', $po->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <select name="status" class="border p-1">
                                        <option value="Approved">Accept</option>
                                        <option value="Rejected">Reject</option>
                                    </select>
                                    <button class="bg-green-500 text-white px-3 py-1">Submit</button>
                                </form>
                            @else
                                <span class="text-gray-500">No Actions</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
