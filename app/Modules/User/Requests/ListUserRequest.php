<?php

namespace App\Modules\User\Requests;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class ListUserRequest extends CommonRequest
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
        $type = implode(',', RoleTypeEnum::values());
        return [
            'per_page' => 'nullable|integer',
            'page' => 'nullable|integer',
            'type' => "nullable|string|in:$type",
        ];
    }
}
