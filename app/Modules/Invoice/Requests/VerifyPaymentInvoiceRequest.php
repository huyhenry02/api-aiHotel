<?php

namespace App\Modules\Invoice\Requests;

use App\Http\Requests\CommonRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class VerifyPaymentInvoiceRequest extends CommonRequest
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
            'invoice_id' => 'required|integer',
            'user_id_paid' => 'required|integer',
            'payment_intent_id' => 'required|string',
        ];
    }
}
