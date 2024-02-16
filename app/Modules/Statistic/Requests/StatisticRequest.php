<?php

namespace App\Modules\Statistic\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class StatisticRequest extends CommonRequest
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
            'start_date' => 'required|date',
            'collection' => 'required|string',
            'type' => 'required|in:day,week,month,year',
        ];
    }
}
