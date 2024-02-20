<?php

namespace App\Modules\Invoice\Repositories\Interfaces;

use App\Modules\Invoice\Models\Invoice;
use App\Repositories\Interfaces\RepositoryInterface;

interface InvoiceInterface extends RepositoryInterface
{
    /**
     * @param Invoice $invoice
     * @param $checkIn
     * @param $checkOut
     * @return void
     */
    public function checkOutInvoice(Invoice $invoice, $checkIn, $checkOut): void;
}
