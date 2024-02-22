<?php

namespace App\Modules\Invoice\Transformers;

use App\Modules\Invoice\Models\Invoice;
use App\Modules\Service\Transformers\ServiceTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'services',

    ];
    public function transform(Invoice $invoice): array
    {
        return [
            'id' => $invoice->id,
            'code' => $invoice->code,
            'status' => $invoice->status,
            'total_day' => $invoice->total_day,
            'total_price' => $invoice->total_price,
            'pay_method' => $invoice->pay_method,
            'payment_intent_id' => $invoice->payment_intent_id,
            'currency' => $invoice->currency,
            'userCheckIn'=> $invoice->userCheckIn ? [
                'id' => $invoice->userCheckIn->id,
                'name' => $invoice->userCheckIn->name,
                'email' => $invoice->userCheckIn->email,
                'role_type' => $invoice->userCheckIn->role_type,
            ] : null,
            'userCheckOut'=> $invoice->userCheckOut ? [
                'id' => $invoice->userCheckOut->id,
                'name' => $invoice->userCheckOut->name,
                'email' => $invoice->userCheckOut->email,
                'role_type' => $invoice->userCheckOut->role_type,
            ] : null,
            'userPaid'=> $invoice->userPaid ? [
                'id' => $invoice->userPaid->id,
                'name' => $invoice->userPaid->name,
                'email' => $invoice->userPaid->email,
                'role_type' => $invoice->userPaid->role_type,
            ] : null,
        ];
    }
    public function includeServices(Invoice $invoice): ?Collection
    {
        if ($invoice->services) {
            return $this->collection($invoice->services, new ServiceTransformer());
        }
        return null;
    }
}
