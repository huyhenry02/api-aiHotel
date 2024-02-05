<?php

namespace App\Modules\Room\Requests;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ListRoomRequest extends CommonRequest
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
            'per_page' => 'nullable|integer',
            'page' => 'nullable|integer',
//            'type' => "nullable|string|in:$type",
        ];
    }
}
