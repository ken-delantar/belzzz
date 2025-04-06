<?php

namespace App\Http\Controllers;

use App\Models\VehicleInventory;
use App\Http\Requests\StoreVehicleInventoryRequest;
use App\Http\Requests\UpdateVehicleInventoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleInventoryController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        $sort = $request->query('sort', 'created_at-desc');

        [$sortField, $sortDirection] = explode('-', $sort);

        $query = VehicleInventory::query();

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $query->orderBy($sortField, $sortDirection);

        $vehicles = $query->paginate(6);

        return view('vehicles.index', compact('vehicles'));
    }

    public function store(StoreVehicleInventoryRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('vehicles', 'public');
        } else {
            $data['image'] = 'vehicles\default_bus.jpg';
        }

        $vehicle = VehicleInventory::create($data);
        flash()->success('Bus added successfully!');
        return response()->json(['redirect' => route('vehicles.index')], 201);
    }

    public function show($id)
    {
        try {
            $vehicle = VehicleInventory::findOrFail($id);
            return response()->json([
                'vehicle_number' => $vehicle->vehicle_number,
                'driver_name' => $vehicle->driver_name,
                'route_from' => $vehicle->route_from,
                'route_to' => $vehicle->route_to,
                'total_capacity' => $vehicle->total_capacity,
                'available_capacity' => $vehicle->available_capacity,
                'status' => $vehicle->status,
                'last_updated' => $vehicle->last_updated,
                'image' => $vehicle->image_url,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Bus not found'], 404);
        }
    }

    public function edit($id)
    {
        try {
            $vehicle = VehicleInventory::findOrFail($id);
            return response()->json([
                'vehicle_number' => $vehicle->vehicle_number,
                'driver_name' => $vehicle->driver_name,
                'route_from' => $vehicle->route_from,
                'route_to' => $vehicle->route_to,
                'total_capacity' => $vehicle->total_capacity,
                'available_capacity' => $vehicle->available_capacity,
                'status' => $vehicle->status,
                'image' => $vehicle->image,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Bus not found'], 404);
        }
    }

    public function update(UpdateVehicleInventoryRequest $request, $id)
    {
        try {
            $vehicle = VehicleInventory::findOrFail($id);
            $data = $request->validated();

            // Handle image update
            if ($request->hasFile('image')) {
                if ($vehicle->image) {
                    Storage::disk('public')->delete($vehicle->image);
                }
                $data['image'] = $request->file('image')->store('vehicles', 'public');
            }

            $vehicle->update($data);
            flash()->success('Bus updated successfully!');
            return response()->json(['redirect' => route('vehicles.index')]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update bus'], 400);
        }
    }

    public function destroy(VehicleInventory $vehicle)
    {
        if ($vehicle->image) {
            Storage::disk('public')->delete($vehicle->image);
        }
        $vehicle->delete();
        flash()->success('Bus deleted successfully!');
        return response()->json(['redirect' => route('vehicles.index')]);
    }
}
