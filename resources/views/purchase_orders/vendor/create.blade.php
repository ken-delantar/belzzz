<x-app-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold">Create Purchase Order</h2>

        <form action="{{ route('purchase_orders.store') }}" method="POST">
            @csrf
            <div>
                <label>PO Number</label>
                <input type="text" name="po_number" required class="border p-2 w-full">
            </div>
            <div>
                <label>Description</label>
                <textarea name="description" required class="border p-2 w-full"></textarea>
            </div>
            <div>
                <label>Amount</label>
                <input type="number" name="amount" required class="border p-2 w-full">
            </div>
            <div>
                <label>Assign Vendor (Optional)</label>
                <select name="vendor_id" class="border p-2 w-full">
                    <option value="">Unassigned</option>
                    @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 mt-3">Create PO</button>
        </form>
    </div>
</x-app-layout>
