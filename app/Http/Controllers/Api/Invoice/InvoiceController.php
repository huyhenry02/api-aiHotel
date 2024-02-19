<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Invoice\Repositories\Interfaces\InvoiceInterface;
use App\Modules\Invoice\Requests\GetOneInvoiceRequest;
use App\Modules\Invoice\Requests\UpdateInvoiceRequest;
use App\Modules\Invoice\Transformers\InvoiceTransformer;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

class InvoiceController extends ApiController
{
    protected InvoiceInterface $invoiceRepo;
    public function __construct(InvoiceInterface $invoiceRepo)
    {
        $this->invoiceRepo = $invoiceRepo;
    }
    public function getListInvoices(PaginationRequest $request): JsonResponse
    {
        try {
            $postData = $request->validated('per_page', 15);
            $invoices = $this->invoiceRepo->getData(perPage: $postData);
            $data = fractal($invoices, new InvoiceTransformer())->parseIncludes(['services'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }

    /**
     * @throws Exception
     */
    public function getOneInvoice(GetOneInvoiceRequest $request): JsonResponse
    {
          $invoice = $this->invoiceRepo->find($request['invoice_id']);
            if (!$invoice) {
                throw new Exception(__('messages.not_found'));
            }
            $data = fractal($invoice, new InvoiceTransformer())->parseIncludes(['services'])->toArray();
            return $this->respondSuccess($data);
    }
    public function updateInvoice(UpdateInvoiceRequest $request): JsonResponse
    {
        $postData = $request->validated();
        try {
            $invoice = $this->invoiceRepo->find($postData['invoice_id']);
            if (!$invoice) {
                throw new Exception(__('messages.not_found'));
            }
            $invoice->fill($postData);
            $invoice->services()->sync($postData['services']);
            $invoice->save();
            $data = fractal($invoice, new InvoiceTransformer())->parseIncludes(['services'])->toArray();
            $response = $this->respondSuccess($data);
        } catch (Exception $e) {
            $response = $this->respondError($e->getMessage());
        }
        return $response;
    }
}
