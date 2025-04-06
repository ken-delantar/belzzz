<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => 'required|exists:vehicle_inventories,id',
            'task_desc' => 'required|string|max:255',
            'task_date' => 'required|date|after_or_equal:today',
            'estimated_cost' => 'required|numeric|min:0',
            'priority' => 'nullable|boolean',
            'assigned_tech' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Please select a vehicle.',
            'task_desc.required' => 'Task description is required.',
            'task_date.required' => 'Maintenance date is required.',
            'task_date.after_or_equal' => 'Date must be today or later.',
            'estimated_cost.required' => 'Estimated cost is required.',
        ];
    }
}
