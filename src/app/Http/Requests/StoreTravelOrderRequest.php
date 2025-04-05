<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTravelOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'destination_id' => 'required|exists:destinations,id',
            'departure_date' => 'required|date',
            'return_date'    => 'required|date|after_or_equal:departure_date',
        ];
    }

    public function messages(): array
    {
        return [
            'destination_id.required' => 'Destination is required.',
            'destination_id.exists'   => 'The selected destination does not exist.',
            'departure_date.required' => 'Departure date is required.',
            'departure_date.date'     => 'Departure date must be a valid date.',
            'return_date.required'    => 'Return date is required.',
            'return_date.date'        => 'Return date must be a valid date.',
            'return_date.after_or_equal' => 'Return date must be after or equal to the departure date.',
        ];
    }
}
