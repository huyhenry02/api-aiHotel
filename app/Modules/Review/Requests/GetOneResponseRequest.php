<?php

namespace App\Modules\Review\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class GetOneResponseRequest extends CommonRequest
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
           'response_id' => 'required|numeric|exists:response_reviews,id',
        ];
    }
}
