<?php

namespace App\Http\Controllers\Api\Payment;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ApiController;
use App\Modules\Payment\Services\PaymentService;

class PaymentApiController extends ApiController
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getClientSecretKey(Request $request): JsonResponse
    {
        $itemId = $request->item_id;
        // TODO: Need to get amount, ... from item id(invoice, ...)
        $amount = 50;
        // TODO: check invoice created payment intent -> get payment intent. Don't exists -> create payment intent and update payment intent id into invoice
        $data = $this->paymentService->createPaymentIntent($itemId, $this->getAmountPayment($amount));

        return $this->respondSuccess($data);
    }

    /**
     * verify payment
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $paymentIntentId = $request->payment_intent_id;
        $itemId = $request->item_id;
        $isValid = false;
        $message = '';

        $paymentIntentData = $this->paymentService->getPaymentIntent($paymentIntentId);
        // TODO: GET Invoice by Item Id and verify amount, currency with $paymentIntentData

        if ($paymentIntentId === $paymentIntentData['id']
            && (
                $paymentIntentData['status'] == 'succeeded' ||
                ($paymentIntentData['status'] == 'requires_capture' && $paymentIntentData['capture_method'] === 'manual')
            )
        ) {
            $isValid = true;
        } else {
            $message = 'Payment Invalid';
        }

        // TODO: need update status invoice when success or error

        return $this->respondSuccess(['is_valid' => $isValid, 'message' => $message]);
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
}
