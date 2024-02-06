<?php

namespace App\Modules\Reservation\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateReservationRequest extends CommonRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'nullable|integer',
            'room_id' => 'required|integer',
            'start_date' => 'nullable|required|date',
            'end_date' => 'required|date',
            'status' => 'string',
            'amount_person' => 'required|integer',
        ];
    }
}
