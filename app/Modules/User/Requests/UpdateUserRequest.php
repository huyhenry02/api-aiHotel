<?php

namespace App\Modules\User\Requests;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Foundation\Application as ContractsApplication;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Application;

class UpdateUserRequest extends CommonRequest
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
            'user_id' => 'required|exists:users,id',
            'name' => 'string',
            'role_type' => "string|in:$type",
            'address' => 'string',
            'phone' => 'string|min:10|unique:users,phone',
            'identification' =>'string|unique:users,identification',
            'password' => 'string|max:255',
            'email' => 'email|string|unique:users,email',
            'age' => 'numeric',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    public function attributes(): Application|array|string|Translator|ContractsApplication|null
    {
        return __('requests.UpdateUserRequest');
    }
}
