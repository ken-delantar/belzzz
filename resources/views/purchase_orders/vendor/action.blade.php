<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold">Purchase Order Details</h2>

        <div class="border p-4">
            <p><strong>PO Number:</strong> {{ $po->po_number }}</p>
            <p><strong>Description:</strong> {{ $po->description }}</p>
            <p><strong>Amount:</strong> ${{ $po->amount }}</p>
            <p><strong>Status:</strong> {{ $po->status }}</p>
        </div>

        @if ($po->status === 'Pending')
            <form action="{{ route('purchase_orders.updateStatus', $po->id) }}" method="POST" class="mt-4">
                @csrf @method('PUT')
                <label class="block">Choose Action</label>
                <select name="status" class="border p-2">
                    <option value="Approved">Accept</option>
                    <option value="Rejected">Reject</option>
                </select>
                <button class="bg-green-500 text-white px-4 py-2">Submit</button>
            </form>
        @else
            <p class="mt-4 text-gray-500">No further actions available.</p>
        @endif
    </div>
</x-app-layout>
