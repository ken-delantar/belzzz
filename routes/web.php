<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ComplianceController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MarketPlaceAdminController;
use App\Http\Controllers\MarketPlaceVendorController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\VehicleInventoryController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\VendorProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::get('/profile/{profile}', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/photo', [ProfileController::class, 'update_profile'])->name('profile.update_profile');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// PURCHASE ORDER MANAGEMENT
Route::middleware('auth')->group(function () {
    // Admin
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase_orders.index');
    Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase_orders.create');
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase_orders.store');
    Route::put('/purchase-orders/{purchaseOrder}/update-status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.updateStatus');

    // Vendor
    Route::get('/vendor/purchase-orders', [PurchaseOrderController::class, 'vendorIndex'])->name('purchase_orders.vendor.index');
    Route::get('/vendor/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'show'])->name('purchase_orders.vendor.show');
    Route::put('/vendor/purchase-orders/{purchaseOrder}/status', [PurchaseOrderController::class, 'updateStatus'])->name('purchase_orders.vendor.updateStatus');
});

Route::resource('vendors', VendorController::class)->middleware('auth');

Route::resource('vehicles', VehicleInventoryController::class)->middleware('auth');

Route::resource('notifications', NotificationController::class)
    ->except(['edit', 'create'])
    ->middleware('auth');

// MARKETPLACE Management
Route::prefix('procurement')->middleware('auth')->group(function () {
    // Customer (Admin) Routes
    Route::get('/admin/store', [MarketPlaceAdminController::class, 'store'])->name('marketplace.admin.store');
    Route::get('/admin/cart', [MarketPlaceAdminController::class, 'cart'])->name('marketplace.admin.cart');
    Route::post('/admin/cart/buy', [MarketPlaceAdminController::class, 'buyNow'])->name('marketplace.admin.cart.buy');
    Route::post('/admin/cart/add', [MarketPlaceAdminController::class, 'addToCart'])->name('marketplace.admin.cart.add');
    Route::post('/admin/cart/remove', [MarketPlaceAdminController::class, 'removeFromCart'])->name('marketplace.admin.cart.remove');
    Route::post('/admin/cart/checkout', [MarketPlaceAdminController::class, 'checkout'])->name('marketplace.admin.cart.checkout');
    Route::post('/admin/cart/remove-selected', [MarketPlaceAdminController::class, 'removeSelected'])->name('marketplace.admin.cart.remove-selected');
    Route::post('/admin/cart/update', [MarketPlaceAdminController::class, 'updateQuantity'])->name('marketplace.admin.cart.update');
    Route::get('/admin/orders', [MarketPlaceAdminController::class, 'orders'])->name('marketplace.admin.orders');
    Route::post('/admin/orders/{order}/cancel', [MarketPlaceAdminController::class, 'cancelOrder'])->name('marketplace.admin.orders.cancel');
    Route::get('/admin/procurement-requests', [MarketPlaceAdminController::class, 'approvalRequests'])->name('marketplace.admin.approval_requests');
    Route::post('admin/orders/{orderId}/approve', [MarketPlaceAdminController::class, 'approveOrder'])->name('marketplace.admin.orders.approve');
    Route::post('admin/orders/{orderId}/reject', [MarketPlaceAdminController::class, 'rejectOrder'])->name('marketplace.admin.orders.reject');

    Route::get('/staff-requests', [MarketPlaceAdminController::class, 'staffRequests'])->name('marketplace.admin.staff_requests');

    // Vendor Routes
    Route::get('/vendor', [MarketPlaceVendorController::class, 'index'])->name('marketplace.vendor.index');
    Route::post('/vendor/products', [MarketPlaceVendorController::class, 'store'])->name('marketplace.vendor.products.store');
    Route::put('/vendor/products/{product}', [MarketPlaceVendorController::class, 'update'])->name('marketplace.vendor.products.update');
    Route::delete('/vendor/products/{product}', [MarketPlaceVendorController::class, 'destroy'])->name('marketplace.vendor.products.destroy');
    Route::get('/vendor/orders', [MarketPlaceVendorController::class, 'vendorOrders'])->name('marketplace.vendor.orders');
    Route::post('/vendor/orders/{purchaseOrder}/approve', [MarketPlaceVendorController::class, 'approveOrder'])->name('marketplace.vendor.orders.approve');
    Route::post('/vendor/orders/{purchaseOrder}/reject', [MarketPlaceVendorController::class, 'rejectOrder'])->name('marketplace.vendor.orders.reject');
    Route::post('/vendor/orders/{purchaseOrder}/update-status', [MarketPlaceVendorController::class, 'updateOrderStatus'])->name('marketplace.vendor.orders.update-status');
    Route::get('/vendor/purchase-order/{id}', [DashboardController::class, 'showPurchaseOrder'])->name('vendor.purchase-order.show');
});

Route::middleware('auth')->group(function () {
    Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('maintenance/store', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::get('/maintenance/{maintenance}', [MaintenanceController::class, 'show'])->name('maintenance.show');
});

Route::middleware('auth')->group(function () {
    Route::get('billings', [BillingController::class, 'index'])->name('billings.index');
});

Route::middleware('auth')->group(function () {
    Route::get('reports/admin', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/vendor', [ReportController::class, 'vendor'])->name('reports.vendor');
    Route::post('reports/store', [ReportController::class, 'store'])->name('reports.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/contracts/create', [ContractController::class, 'create'])->name('contracts.create');
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts.index');
    Route::get('/contracts/admin', [ContractController::class, 'admin'])->name('contracts.admin');
    Route::get('/contracts/upload', [ContractController::class, 'uploadForm'])->name('contracts.upload');
    Route::post('/contracts/upload', [ContractController::class, 'store'])->name('contracts.store');
    Route::post('/contracts/{id}/approve', [ContractController::class, 'approve'])->name('contracts.approve');
    Route::post('/contracts/{id}/decline', [ContractController::class, 'decline'])->name('contracts.decline');
    Route::get('/contracts/preview/{id}', [ContractController::class, 'preview'])->name('contracts.preview');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/proposals', [ProposalController::class, 'index'])->name('proposals.index');
    Route::get('/proposals/admin', [ProposalController::class, 'admin'])->name('proposals.admin');
    Route::delete('/proposals/{id}/edit', [ProposalController::class, 'edit'])->name('proposals.edit');
    Route::get('/proposals/{id}', [ProposalController::class, 'show'])->name('proposals.show');
    Route::post('/proposals', [ProposalController::class, 'store'])->name('proposals.store');
    Route::delete('/proposals/{id}', [ProposalController::class, 'destroy'])->name('proposals.destroy');
    Route::get('/proposals/approved', [ProposalController::class, 'approved'])->name('proposals.approved');
    Route::get('/proposals/preview/{id}', [ProposalController::class, 'preview'])->name('proposals.preview');
    Route::post('/proposals/{id}/approve', [ProposalController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{id}/decline', [ProposalController::class, 'decline'])->name('proposals.decline');
    Route::get('/proposals/generate', [ProposalController::class, 'generateCustomContract'])->name('proposals.generate');
});

Route::middleware('auth')->group(function () {
    Route::get('/usermanagement', [UserManagementController::class, 'index'])->name('usermanagement.index');
    Route::get('/usermanagement/create', [UserManagementController::class, 'create'])->name('usermanagement.create');
    Route::post('/usermanagement', [UserManagementController::class, 'store'])->name('usermanagement.store');
    Route::get('/usermanagement/{user}', [UserManagementController::class, 'show'])->name('usermanagement.show');
    Route::get('/usermanagement/{user}/edit', [UserManagementController::class, 'edit'])->name('usermanagement.edit');
    Route::put('/usermanagement/{user}', [UserManagementController::class, 'update'])->name('usermanagement.update');
    Route::delete('/usermanagement/{user}', [UserManagementController::class, 'destroy'])->name('usermanagement.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('compliance', [ComplianceController::class, 'index'])->name('compliance.index');
    Route::post('/upload_file', [ComplianceController::class, 'upload'])->name('vendor_compliance.upload');
    Route::get('/submissions', [ComplianceController::class, 'list'])->name('vendor_compliance.list');
    Route::get('/vendor_compliance/list', [ComplianceController::class, 'list'])->name('vendor_compliance.list');

    Route::get('/staff/compliance', [ComplianceController::class, 'secretary'])->name('compliance.secretary');
});

require __DIR__ . '/auth.php';
