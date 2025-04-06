<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Proposal;
use App\Models\PurchaseOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'Admin') {
            return $this->admin();
        } elseif (Auth::user()->role === 'Vendor') {
            return $this->vendor();
        } elseif (Auth::user()->role === 'Driver') {
            return redirect()->route('maintenance.index');
        } elseif (Auth::user()->role === 'Staff') {
            return redirect()->route('marketplace.admin.store');
        } else if (Auth::user()->role === 'Secretary'){
            return $this->secretary();
        }
        return view('welcome');
    }

    protected function secretary(){
        // Registered Vendors
        $registeredVendorsCount = User::where('role', 'Vendor')->count();
        $vendorChange = User::where('role', 'Vendor')
            ->where('created_at', '>=', now()->subMonth())
            ->count(); // New vendors this month as "change"

        // Active RFPs
        $activeRfpsCount = Proposal::where('admin_status', 'pending')
            ->where('valid_until', '>=', now())
            ->count();
        $newRfpsCount = Proposal::where('admin_status', 'pending')
            ->where('created_at', '>=', now()->subMonth())
            ->count();

        // Proposals Submitted
        $proposalsSubmittedCount = Proposal::count();
        $proposalsChange = Proposal::where('created_at', '>=', now()->subMonth())->count();

        // Contracts Awarded (Assuming a Contract model or approved proposals)
        $contractsAwardedCount = Proposal::where('admin_status', 'approved')->count();
        $contractsChange = Proposal::where('admin_status', 'approved')
            ->where('updated_at', '>=', now()->subMonth())
            ->count();

        // Monthly Vendor Registrations (last 9 months)
        $monthlyRegistrations = User::where('role', 'Vendor')
            ->where('created_at', '>=', now()->subMonths(9))
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%b") as month')
            ->groupBy(DB::raw('MONTH(created_at), DATE_FORMAT(created_at, "%b")')) // Group by month number and name
            ->orderBy(DB::raw('MONTH(created_at)')) // Order by month number (1-12)
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Recent Vendor Activities (e.g., profile updates, proposal submissions)
        $recentActivities = collect([
            [
                'vendor_name' => 'ABC Corp',
                'action' => 'updated their profile',
                'time' => now()->diffForHumans(),
            ],
            [
                'vendor_name' => null,
                'proposal_id' => 'RFP-2023-001',
                'action' => 'submitted',
                'time' => now()->subDays(2)->diffForHumans(),
            ],
        ])->concat(
            Proposal::latest()
                ->take(3)
                ->get()
                ->map(function ($proposal) {
                    return [
                        'vendor_name' => $proposal->vendor_name,
                        'proposal_id' => $proposal->id,
                        'action' => 'submitted a proposal',
                        'time' => $proposal->created_at->diffForHumans(),
                    ];
                })
        );

        // Active RFPs & Proposals
        $activeProposals = Proposal::whereIn('admin_status', ['pending', 'under_review', 'approved'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.secretary', compact(
            'registeredVendorsCount',
            'vendorChange',
            'activeRfpsCount',
            'newRfpsCount',
            'proposalsSubmittedCount',
            'proposalsChange',
            'contractsAwardedCount',
            'contractsChange',
            'monthlyRegistrations',
            'recentActivities',
            'activeProposals'
        ));
    }

    protected function admin()
    {
        // Registered Vendors
        $registeredVendorsCount = User::where('role', 'Vendor')->count();
        $vendorChange = User::where('role', 'Vendor')
            ->where('created_at', '>=', now()->subMonth())
            ->count(); // New vendors this month as "change"

        // Active RFPs
        $activeRfpsCount = Proposal::where('admin_status', 'pending')
            ->where('valid_until', '>=', now())
            ->count();
        $newRfpsCount = Proposal::where('admin_status', 'pending')
            ->where('created_at', '>=', now()->subMonth())
            ->count();

        // Proposals Submitted
        $proposalsSubmittedCount = Proposal::count();
        $proposalsChange = Proposal::where('created_at', '>=', now()->subMonth())->count();

        // Contracts Awarded (Assuming a Contract model or approved proposals)
        $contractsAwardedCount = Proposal::where('admin_status', 'approved')->count();
        $contractsChange = Proposal::where('admin_status', 'approved')
            ->where('updated_at', '>=', now()->subMonth())
            ->count();

        // Monthly Vendor Registrations (last 9 months)
        $monthlyRegistrations = User::where('role', 'Vendor')
            ->where('created_at', '>=', now()->subMonths(9))
            ->selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%b") as month')
            ->groupBy(DB::raw('MONTH(created_at), DATE_FORMAT(created_at, "%b")')) // Group by month number and name
            ->orderBy(DB::raw('MONTH(created_at)')) // Order by month number (1-12)
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Recent Vendor Activities (e.g., profile updates, proposal submissions)
        $recentActivities = collect([
            [
                'vendor_name' => 'ABC Corp',
                'action' => 'updated their profile',
                'time' => now()->diffForHumans(),
            ],
            [
                'vendor_name' => null,
                'proposal_id' => 'RFP-2023-001',
                'action' => 'submitted',
                'time' => now()->subDays(2)->diffForHumans(),
            ],
        ])->concat(
            Proposal::latest()
                ->take(3)
                ->get()
                ->map(function ($proposal) {
                    return [
                        'vendor_name' => $proposal->vendor_name,
                        'proposal_id' => $proposal->id,
                        'action' => 'submitted a proposal',
                        'time' => $proposal->created_at->diffForHumans(),
                    ];
                })
        );

        // Active RFPs & Proposals
        $activeProposals = Proposal::whereIn('admin_status', ['pending', 'under_review', 'approved'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'registeredVendorsCount',
            'vendorChange',
            'activeRfpsCount',
            'newRfpsCount',
            'proposalsSubmittedCount',
            'proposalsChange',
            'contractsAwardedCount',
            'contractsChange',
            'monthlyRegistrations',
            'recentActivities',
            'activeProposals'
        ));
    }

    public function showPurchaseOrder($id)
    {
        $purchaseOrder = PurchaseOrder::where('vendor_id', Auth::user()->vendor->id)
            ->findOrFail($id);
        return response()->json($purchaseOrder);
    }

    public function vendor()
    {
        $vendorId = Auth::user()->vendor->id;
        $userId = Auth::id();

        $completedPOs = PurchaseOrder::where('vendor_id', $vendorId)
            ->where('status', 'Completed')
            ->where('updated_at', '>=', now()->subMonth())
            ->count();
        $totalPOs = PurchaseOrder::where('vendor_id', $vendorId)
            ->whereIn('status', ['Completed', 'Canceled'])
            ->where('updated_at', '>=', now()->subMonth())
            ->count();
        $onTimeDelivery = $totalPOs > 0 ? round(($completedPOs / $totalPOs) * 100) : 0;

        $pendingTasksCount = Maintenance::whereHas('vehicle', function ($query) use ($vendorId) {
            $query->where('assigned_tech', $vendorId);
        })->where('status', 'pending')->count();

        $purchaseOrders = PurchaseOrder::where('vendor_id', $vendorId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $bidOpportunities = Proposal::where('admin_status', 'pending')
            ->where('valid_until', '>=', now())
            ->orderBy('valid_until', 'asc')
            ->limit(5)
            ->get();

        $submittedProposals = Proposal::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.vendor', compact(
            'onTimeDelivery',
            'pendingTasksCount',
            'purchaseOrders',
            'bidOpportunities',
            'submittedProposals'
        ));
    }
}
