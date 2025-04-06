<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketPlaceAdminController extends Controller
{
    public function store()
    {
        $products = Product::with('vendor.user')->get();
        $pendingApprovalCount = 0; // Default for non-Admins or unauthenticated users

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'Admin') {
                // Admins see the count of all cart items and pending approval requests
                $cartItems = Cart::with('product')->get();
                $pendingApprovalCount = Order::where('approval_status', 'Pending Approval')->count();
            } else {
                // Staff (and others) see only their own cart items
                $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            }
        } else {
            $cartItems = collect(); // Empty collection for unauthenticated users
        }

        return view('marketplace.admin.store', compact('products', 'cartItems', 'pendingApprovalCount'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('products.vendor.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.admin.orders', compact('orders'));
    }

    public function cancelOrder(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);

        if ($order->status !== 'Pending') {
            flash()->error('Only pending procurement requests can be canceled.');
            return redirect()->route('marketplace.admin.orders');
        }

        foreach ($order->products as $product) {
            if ($product->type === 'items') {
                $product->increment('stock', $product->pivot->quantity);
            }
        }

        PurchaseOrder::whereIn('description', $order->products->map(fn($p) => "Order for {$p->name} (Qty: {$p->pivot->quantity})"))
            ->where('status', 'Pending')
            ->update(['status' => 'Canceled']);

        $order->update(['status' => 'Canceled']);

        flash()->success("Procurement request #{$order->id} has been canceled.");
        return redirect()->route('marketplace.admin.orders');
    }

    public function cart()
    {
        $user = Auth::user();

        if ($user->role === 'Admin') {
            // Admins see all cart records
            $cartItems = Cart::with('product.vendor.user', 'user')->get();
        } else {
            // Staff (and others) see only their own cart
            $cartItems = Cart::where('user_id', Auth::id())->with('product.vendor.user')->get();
        }

        return view('marketplace.admin.cart', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        $quantity = $request->validate([
            'quantity' => 'required|integer|min:1' . ($product->type === 'items' ? "|max:{$product->stock}" : ''),
        ])['quantity'];

        if ($product->type === 'items' && $product->stock < $quantity) {
            flash()->error('Insufficient stock available for this procurement request!');
            return redirect()->route('marketplace.admin.store');
        }

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->type === 'items' && $newQuantity > $product->stock) {
                flash()->error('Requested quantity exceeds available stock!');
                return redirect()->route('marketplace.admin.store');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        if ($product->type === 'items') {
            $product->decrement('stock', $quantity);
        }

        // flash()->success("Added $quantity x {$product->name} to your procurement list!");
        return redirect()->route('marketplace.admin.cart', ['buy_product_id' => $product->id]);
    }

    public function removeFromCart(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $request->product_id)->first();

        if ($cartItem) {
            $product = $cartItem->product;
            if ($product->type === 'items') {
                $product->increment('stock', $cartItem->quantity);
            }
            $cartItem->delete();
            flash()->success("Removed {$product->name} from your procurement list!");
        } else {
            flash()->error('Item not found in your procurement list.');
        }

        return redirect()->route('marketplace.admin.cart');
    }

    public function removeSelected(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);
        if (empty($selectedItems)) {
            flash()->error('No items selected for removal from your procurement list.');
            return redirect()->route('marketplace.admin.cart');
        }

        $cartItems = Cart::where('user_id', Auth::id())->whereIn('id', $selectedItems)->get();
        if ($cartItems->isEmpty()) {
            flash()->error('No valid items selected for removal from your procurement list.');
            return redirect()->route('marketplace.admin.cart');
        }

        foreach ($cartItems as $cartItem) {
            if ($cartItem->product->type === 'items') {
                $cartItem->product->increment('stock', $cartItem->quantity);
            }
            $cartItem->delete();
        }

        flash()->success('Selected items removed from your procurement list.');
        return redirect()->route('marketplace.admin.cart');
    }

    public function updateQuantity(Request $request)
    {
        $cartItem = Cart::where('user_id', Auth::id())->findOrFail($request->cart_item_id);
        $change = $request->change;

        $newQuantity = $cartItem->quantity + $change;
        if ($newQuantity < 1) {
            $newQuantity = 1;
        } elseif ($cartItem->product->type === 'items' && $newQuantity > $cartItem->product->stock) {
            $newQuantity = $cartItem->product->stock;
        }

        $quantityDifference = $newQuantity - $cartItem->quantity;
        if ($quantityDifference !== 0 && $cartItem->product->type === 'items') {
            $cartItem->product->increment('stock', -$quantityDifference);
        }

        $cartItem->update(['quantity' => $newQuantity]);

        return response()->json(['success' => true, 'new_quantity' => $newQuantity]);
    }

    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected_items', []);
        if (empty($selectedIds)) {
            flash()->error('Please select at least one item to proceed with procurement.');
            return redirect()->route('marketplace.admin.cart');
        }

        $cartItems = Cart::where('user_id', Auth::id())->whereIn('id', $selectedIds)->get();
        if ($cartItems->isEmpty()) {
            flash()->error('No valid items selected for procurement.');
            return redirect()->route('marketplace.admin.cart');
        }

        $user = Auth::user();
        $approvalStatus = $user->role === 'Admin' ? 'Approved' : 'Pending Approval';
        // dd($approvalStatus);
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
            'status' => 'Pending',
            'approval_status' => $approvalStatus,
        ]);

        foreach ($cartItems->groupBy('product.vendor_id') as $vendorId => $items) {
            $vendorTotal = $items->sum(fn($item) => $item->product->price * $item->quantity);
            $poNumber = 'PO-' . strtoupper(uniqid());
            $description = $items->map(fn($item) => "Order for {$item->product->name} (Qty: {$item->quantity})")->implode(', ');

            PurchaseOrder::create([
                'order_id' => $order->id,
                'vendor_id' => $vendorId,
                'po_number' => $poNumber,
                'description' => $description,
                'amount' => $vendorTotal,
                'status' => 'Pending',
            ]);
        }

        $order->products()->attach(
            $cartItems->mapWithKeys(fn($item) => [
                $item->product_id => ['quantity' => $item->quantity, 'price' => $item->product->price]
            ])->toArray()
        );

        Cart::whereIn('id', $selectedIds)->delete();

        flash()->success('Procurement request submitted successfully!');
        if ($user->role === 'Admin') {
            return redirect()->route('marketplace.admin.orders');
        } else {
            return redirect()->route('marketplace.admin.staff_requests');
        }
    }

    public function buyNow(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        if ($product->type === 'items' && $product->stock <= 0) {
            flash()->error('Item is out of stock for procurement!');
            return redirect()->route('marketplace.admin.store');
        }

        $cartItem = Cart::where('user_id', Auth::id())->where('product_id', $product->id)->first();
        $quantity = 1;

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->type === 'items' && $newQuantity > $product->stock) {
                flash()->error('Requested quantity exceeds available stock!');
                return redirect()->route('marketplace.admin.store');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            $cartItem = Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }

        if ($product->type === 'items') {
            $product->decrement('stock', $quantity);
        }

        if (Auth::user()->role === 'Admin') {
            return redirect()->route('marketplace.admin.cart', ['buy_product_id' => $product->id]);
        } else {
            flash()->success("Added $quantity x {$product->name} to your procurement list!");
            return redirect()->route('marketplace.admin.store');
        }
    }

    // New method for Admin to view and manage approval requests
    public function approvalRequests()
    {
        $orders = Order::where('approval_status', 'Pending Approval')
            ->whereNot('status', 'Canceled') // Exclude orders with status "Canceled"
            ->with('products.vendor.user', 'user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.admin.approval_requests', compact('orders'));
    }

    public function approveOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (Auth::user()->role !== 'Admin') {
            flash()->error('Only Admins can approve procurement requests.');
            return redirect()->route('marketplace.admin.orders');
        }

        if ($order->approval_status !== 'Pending Approval') {
            flash()->error('This procurement request is not pending approval.');
            return redirect()->route('marketplace.admin.approval_requests');
        }

        $order->update(['approval_status' => 'Approved']);
        flash()->success("Procurement request #{$order->id} has been approved.");
        return redirect()->route('marketplace.admin.approval_requests');
    }

    public function rejectOrder(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (Auth::user()->role !== 'Admin') {
            flash()->error('Only Admins can reject procurement requests.');
            return redirect()->route('marketplace.admin.orders');
        }

        if ($order->approval_status !== 'Pending Approval') {
            flash()->error('This procurement request is not pending approval.');
            return redirect()->route('marketplace.admin.approval_requests');
        }

        foreach ($order->products as $product) {
            if ($product->type === 'items') {
                $product->increment('stock', $product->pivot->quantity);
            }
        }

        $order->update(['approval_status' => 'Rejected', 'status' => 'Canceled']);
        $order->purchaseOrders()->update(['status' => 'Canceled']);

        flash()->success("Procurement request #{$order->id} has been rejected.");
        return redirect()->route('marketplace.admin.approval_requests');
    }

    public function staffRequests()
    {
        // Ensure only Staff can access this
        if (Auth::user()->role !== 'Staff') {
            flash()->error('Only Staff users can view their procurement requests.');
            return redirect()->route('marketplace.admin.store');
        }

        $requests = Order::where('user_id', Auth::id())
            ->with('products.vendor.user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('marketplace.admin.staff_requests', compact('requests'));
    }
}
