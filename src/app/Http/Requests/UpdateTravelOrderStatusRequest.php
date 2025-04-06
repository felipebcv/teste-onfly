<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTravelOrderStatusRequest extends FormRequest
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
            'status_id' => 'required|in:2,3|exists:travel_order_statuses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'status_id.required' => 'Status is required.',
            'status_id.in'       => 'The status must be either approved (2) or cancelled (3).',
            'status_id.exists'   => 'The selected status does not exist.',
        ];
    }
}
