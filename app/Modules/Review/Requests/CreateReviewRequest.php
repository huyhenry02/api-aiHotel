<?php

namespace App\Modules\Review\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateReviewRequest extends CommonRequest
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
            'content' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
            'room_id' => 'required|numeric|exists:rooms,id',
        ];
    }
}
