<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\ApiController;
use App\Http\Requests\PaginationRequest;
use App\Modules\Invoice\Repositories\Interfaces\InvoiceInterface;
use App\Modules\Invoice\Requests\GetOneInvoiceRequest;
use App\Modules\Invoice\Requests\UpdateInvoiceRequest;
use App\Modules\Invoice\Requests\VerifyPaymentInvoiceRequest;
use App\Modules\Invoice\Transformers\InvoiceTransformer;
use App\Modules\Payment\Services\PaymentService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

class InvoiceController extends ApiController
{
    protected InvoiceInterface $invoiceRepo;
    protected PaymentService $paymentService;

    public function __construct(InvoiceInterface $invoiceRepo, PaymentService $paymentService)
    {
        $this->invoiceRepo = $invoiceRepo;
        $this->paymentService = $paymentService;
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

    public function getClientSecretKey(GetOneInvoiceRequest $request): JsonResponse
    {
        $postData = $request->validated();
        $itemId = $postData['invoice_id'];
        $invoice = $this->invoiceRepo->find($itemId);
        if (!$invoice) {
            return $this->respondError(__('messages.not_found'));
        }
        $amount = $invoice->total_price;
        if (!$amount) {
            return $this->respondError(__('messages.amount_invalid'));
        }
        $data = $this->paymentService->createPaymentIntent(itemId: $itemId, amount: $this->getAmountPayment($amount));
        return $this->respondSuccess($data);
    }

    /**
     * generate amount of stripe
     * @param $amount
     * @return int
     */
    public function getAmountPayment($amount): int
    {
        return $amount * 100;
    }

    public function verifyPaymentInvoice(VerifyPaymentInvoiceRequest $request): JsonResponse
    {
        $postData = $request->validated();
        $paymentIntentId = $postData['payment_intent_id'];
        $itemId = $postData['invoice_id'];
        $isValid = false;
        $paymentIntentData = $this->paymentService->getPaymentIntent($paymentIntentId);
        $invoice = $this->invoiceRepo->find($itemId);
        if (!$invoice) {
            return $this->respondError(__('messages.not_found'));
        }
        $invoiceAmountInCents = $this->getAmountPayment($invoice->total_price);

        if ($invoiceAmountInCents !== $paymentIntentData['amount']) {
            return $this->respondError('Payment amount does not match invoice amount');
        }
        if ($paymentIntentId === $paymentIntentData['id']
            && (
                $paymentIntentData['status'] == 'succeeded' ||
                ($paymentIntentData['status'] == 'requires_payment_method' && $paymentIntentData['capture_method'] === 'automatic')
            )
        ) {
            $invoice = $this->invoiceRepo->update($itemId, [
                'status' => 'paid',
                'payment_intent_id' => $paymentIntentId,
                'paid_at' => now(),
                'payment_method' => $paymentIntentData['payment_method'],
                'currency' => $paymentIntentData['currency'],
                'user_id_paid' => $postData['user_id_paid'],
            ]);
            $message = 'Payment Success';
            $isValid = true;
        } else {
            $message = 'Payment Invalid';
            $invoice->status = 'canceled';
        }
        $invoice->save();
        return $this->respondSuccess(['is_valid' => $isValid, 'message' => $message]);
    }
}
