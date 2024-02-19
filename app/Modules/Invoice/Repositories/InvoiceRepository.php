<?php

namespace App\Modules\Invoice\Repositories;

use App\Modules\Example\Repositories\Interfaces\ExampleInterface;
use App\Modules\Invoice\Models\Invoice;
use App\Modules\Invoice\Repositories\Interfaces\InvoiceInterface;
use App\Repositories\BaseRepository;
use Carbon\Carbon;


class InvoiceRepository extends BaseRepository implements InvoiceInterface
{
    /**
     * getModel
     *
     * @return string
     */
    public function getModel(): string
    {
        return Invoice::class;
    }

    /**
     * @param Invoice $invoice
     * @param $checkIn
     * @param $checkOut
     * @return void
     */
    public function calculateTotalDates(Invoice $invoice, $checkIn, $checkOut): void
    {
        $checkIn = is_string($checkIn) ? Carbon::parse($checkIn) : $checkIn;
        $checkOut = is_string($checkOut) ? Carbon::parse($checkOut) : $checkOut;
        $totalDays = $checkIn->diffInDays($checkOut);
        if ($checkIn->hour < 14) {
            $totalDays++;
        }
        if ($checkOut->hour >= 12) {
            $totalDays++;
        }
        $invoice->total_day = $totalDays;
        $invoice->save();
    }
}
