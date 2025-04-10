<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateVehicleInventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'Admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicleId = $this->route('vehicle'); // Get the vehicle ID from the route

        return [
            'vehicle_number' => "required|string|max:255|unique:vehicle_inventories,vehicle_number,{$vehicleId}",
            'driver_name' => 'required|string|max:255',
            'route_from' => 'required|string|max:255',
            'route_to' => 'required|string|max:255',
            'total_capacity' => 'required|numeric|min:1',
            'available_capacity' => 'required|numeric|min:0|lte:total_capacity',
            'status' => 'required|in:ready,maintenance',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'vehicle_number.required' => 'The bus number is required.',
            'vehicle_number.unique' => 'This bus number is already in use by another bus.',
            'driver_name.required' => 'The driver name is required.',
            'route_from.required' => 'The starting route is required.',
            'route_to.required' => 'The destination route is required.',
            'total_capacity.required' => 'The total seats field is required.',
            'total_capacity.min' => 'The total seats must be at least 1.',
            'available_capacity.required' => 'The available seats field is required.',
            'available_capacity.lte' => 'Available seats cannot exceed total seats.',
            'status.required' => 'The status is required.',
            'status.in' => 'The status must be either "ready" or "maintenance".',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, or gif.',
            'image.max' => 'The image may not be larger than 2MB.',
        ];
    }
}
