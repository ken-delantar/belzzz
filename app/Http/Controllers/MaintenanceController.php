<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\VehicleInventory;
use Illuminate\Http\Request;
use App\Models\Vendor;

use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maintenances = Maintenance::with('vehicle', 'createdBy')
            ->where('status', 'pending')
            ->orderBy('maintenance_date', 'asc')
            ->paginate(3);

        $overdueTasks = Maintenance::with('vehicle', 'createdBy')
            ->where('maintenance_date', '<', now())
            ->where('status', '!=', 'completed')
            ->get();

        $dueSoonTasks = Maintenance::with('vehicle', 'createdBy')
            ->whereBetween('maintenance_date', [now(), now()->addDays(3)])
            ->where('status', '!=', 'completed')
            ->get();

        $pendingTasksList = Maintenance::with('vehicle')
            ->where('status', 'pending')
            ->get();
        $pendingTasksCount = $pendingTasksList->count();

        $completedThisMonthList = Maintenance::with('vehicle')
            ->where('status', 'completed')
            ->whereMonth('maintenance_date', now()->month)
            ->get();
        $completedThisMonthCount = $completedThisMonthList->count();

        $activeVehiclesList = VehicleInventory::where('status', 'ready')
            ->get();
        $activeVehiclesCount = $activeVehiclesList->count();


        $vendorsOptions = Vendor::all();
        $vehicles = VehicleInventory::all();

        return view('maintenance.index', compact(
            'maintenances',
            'overdueTasks',
            'dueSoonTasks',
            'pendingTasksList',
            'pendingTasksCount',
            'completedThisMonthList',
            'completedThisMonthCount',
            'activeVehiclesList',
            'activeVehiclesCount',
            'vendorsOptions',
            'vehicles'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $maintenance = Maintenance::create([
                'vehicle_id' => $request->vehicle_id,
                'description' => $request->task_desc,
                'maintenance_date' => $request->task_date,
                'cost' => $request->estimated_cost,
                'isPriority' => $request->priority ?? 0,
                'assigned_tech' => $request->assigned_tech,
                'notes' => $request->notes,
                'created_by' => Auth::user()->id,
            ]);

            // Check if it's a priority task and customize the message
            $message = $maintenance->isPriority
                ? 'Priority maintenance task created successfully!'
                : 'Maintenance task created successfully!';

            return redirect()->route('maintenance.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('maintenance.index')
                ->with('error', 'Failed to create maintenance task: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        return response()->json($maintenance->load('vehicle', 'createdBy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
    }
}
