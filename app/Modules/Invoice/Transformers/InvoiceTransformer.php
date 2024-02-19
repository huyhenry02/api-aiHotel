<?php

namespace App\Modules\Invoice\Transformers;

use App\Modules\Example\Models\Example;
use App\Modules\Invoice\Models\Invoice;
use App\Modules\Service\Transformers\ServiceTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\TransformerAbstract;

class InvoiceTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'services'
    ];
    public function transform(Invoice $invoice): array
    {
        return [
            'id' => $invoice->id,
            'code' => $invoice->code,
            'status' => $invoice->status,
            'total_date' => $invoice->total_date,
            'total' => $invoice->total,
            'pay_method' => $invoice->pay_method,
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
