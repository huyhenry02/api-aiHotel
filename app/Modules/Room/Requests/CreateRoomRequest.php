<?php

namespace App\Modules\Room\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateRoomRequest extends CommonRequest
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
            'hotel_id' => 'required|integer',
            'room_type_id' => 'required|integer',
            'floor' => 'required|integer',
            'code' => 'string',
        ];
    }
}
