<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Http\Requests\StorePurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            if (!Auth::check()) return redirect()->route('login');
            $purchaseOrders = PurchaseOrder::with('vendor')
                ->latest()
                ->paginate(15);

            $vendors = Vendor::whereNull('deleted_at')->get();
            $poNumber = $this->generatePurchaseOrderNumber();

            return view('purchase_orders.admin.index', compact('purchaseOrders', 'vendors', 'poNumber'));
        } catch (\Exception $e) {
            flash()->error('Failed to load purchase orders: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display a listing of purchase orders for authenticated vendor
     *
     * @return \Illuminate\View\View
     */
    public function vendorIndex()
    {
        try {
            if (!Auth::check()) return redirect()->route('login');
            $vendorId = Auth::id();
            $purchaseOrders = PurchaseOrder::where('vendor_id', $vendorId)
                ->with('vendor')
                ->latest()
                ->paginate(15);

            return view('purchase_orders.vendor.index', compact('purchaseOrders'));
        } catch (\Exception $e) {
            flash()->error('Failed to load vendor purchase orders: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new purchase order
     *
     * @return \Illuminate\View\View
     */

    public function show(PurchaseOrder $purchaseOrder)
    {
        // Ensure the PO belongs to the authenticated vendor
        if ($purchaseOrder->vendor_id !== Auth::user()->vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json($purchaseOrder);
    }

    public function create()
    {
        try {
            $vendors = Vendor::whereNull('deleted_at')->get();

            $poNumber = $this->generatePurchaseOrderNumber();

            return view('purchase_orders.admin.create', compact('vendors', 'poNumber'));
        } catch (\Exception $e) {
            flash()->error('Failed to load create form: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Generate a unique purchase order number
     *
     * @return string
     */
    private function generatePurchaseOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastPo = PurchaseOrder::latest()->first();
        $sequence = $lastPo ? (int)substr($lastPo->po_number, -4) + 1 : 1;

        return "PO-{$date}-" . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Store a newly created purchase order
     *
     * @param StorePurchaseOrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StorePurchaseOrderRequest $request)
    {
        try {
            $data = $request->validated();
            $data['po_number'] = $this->generatePurchaseOrderNumber();
            $data['created_by'] = Auth::id();

            PurchaseOrder::create($data);

            flash()->success('Purchase Order Created Successfully!');

            return redirect()
                ->route('purchase_orders.index');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create purchase order: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update an existing purchase order
     *
     * @param UpdatePurchaseOrderRequest $request
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(StorePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $purchaseOrder->update([
                'status' => $request->validated()['status'],
                'updated_by' => Auth::id()
            ]);

            return back()->with('success', 'Status Updated Successfully!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to update status: ' . $e->getMessage());
        }
    }
}
