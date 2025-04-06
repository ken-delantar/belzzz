<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);

        // Check stock for items
        if ($product->type === 'items' && $product->stock <= 0) {
            return redirect()->route('store.index')->with('error', 'Product out of stock!');
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'type' => $product->type,
                'vendor_id' => $product->vendor_id,
            ];
        }

        // Update stock for items
        if ($product->type === 'items') {
            $product->decrement('stock');
        }

        session()->put('cart', $cart);
        return redirect()->route('store.index')->with('success', 'Added to cart!');
    }

    public function view()
    {
        return view('cart');
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $product->type === 'items') {
                $product->increment('stock', $cart[$productId]['quantity']); // Restore stock
            }
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.view')->with('success', 'Item removed from cart!');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('store.index')->with('error', 'Cart is empty!');
        }

        $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));
        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $total,
            'status' => 'Pending',
        ]);

        foreach ($cart as $id => $item) {
            $order->products()->attach($id, [
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Optionally notify vendor (e.g., via email or PO creation)
            // Example: Create a PO for the vendor
            \App\Models\PurchaseOrder::create([
                'po_number' => 'PO' . now()->format('YmdHis'),
                'vendor_id' => $item['vendor_id'],
                'type' => $item['type'],
                'description' => "Order for {$item['name']} (Qty: {$item['quantity']})",
                'amount' => $item['price'] * $item['quantity'],
                'status' => 'Pending',
            ]);
        }

        session()->forget('cart');
        return redirect()->route('store.index')->with('success', 'Order placed successfully!');
    }
}
