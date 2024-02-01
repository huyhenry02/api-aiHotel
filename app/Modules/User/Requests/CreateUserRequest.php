<?php

namespace App\Modules\User\Requests;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Application;

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
            'phone' => 'required|string|min:10|unique:users,phone',
            'identification' =>'required|string',
            'password' => 'required|string|max:255',
            'email' => 'required|email|string|unique:users,email',
            'age' => 'required|numeric',
        ];
    }
    public function attributes(): Application|array|string|Translator|ContractsApplication|null
    {
        return __('requests.GetUserByUserIdRequest');
    }
}
