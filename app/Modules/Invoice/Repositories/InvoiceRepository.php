<?php

namespace App\Modules\Invoice\Repositories;

use App\Modules\Example\Repositories\Interfaces\ExampleInterface;
use App\Modules\Invoice\Models\Invoice;
use App\Modules\Invoice\Repositories\Interfaces\InvoiceInterface;
use App\Repositories\BaseRepository;


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
}
