<?php

namespace App\Modules\Room\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateRoomTypeRequest extends CommonRequest
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
            'room_type_id' => 'required|integer',
            'name' => 'string|max:255',
            'code' => 'string|max:255',
            'price' => 'numeric',
            'description' => 'nullable|string|max:255',

        ];
    }
}
