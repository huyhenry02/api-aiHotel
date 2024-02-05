<?php

namespace App\Modules\Room\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRoomRequest extends CommonRequest
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
            'room_id' => 'required|integer',
            'hotel_id' => 'nullable|integer',
            'room_type_id' => 'nullable|integer',
            'floor' => 'nullable|integer',
            'code' => 'string',
        ];
    }
}
