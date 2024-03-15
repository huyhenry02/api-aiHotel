<?php

namespace App\Modules\Review\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class FilterReviewsRequest extends CommonRequest
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
            'hotel_id' => 'integer',
            'rating' => 'numeric|min:1|max:5',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
