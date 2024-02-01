<?php

namespace App\Modules\User\Requests;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateUserRequest extends CommonRequest
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
            'name' => 'required|string',
            'role_type' => "string|in:$type",
            'address' => 'required|string',
            'phone' => 'required|string',
            'identification' => 'required|string',
            'password' => 'required|string',
            'email' => 'required|string|unique:users',
            'age' => 'required',
            'code' => 'string',
        ];
    }
}
