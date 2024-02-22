<?php

namespace App\Modules\Hotel\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdateHotelRequest extends CommonRequest
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
        $this->dd($this->all());
        return [
            'hotel_id' => 'required|integer',
            'name' => 'string|max:255',
            'address' => 'string|max:255',
            'room_types' => 'array',
            'banner' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }
}
