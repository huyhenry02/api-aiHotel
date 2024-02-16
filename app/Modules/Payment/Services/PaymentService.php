<?php

namespace App\Modules\Payment\Services;

class PaymentService
{
    protected $paymentService;

    public function __construct()
    {
        if (!$this->paymentService) {
            $this->paymentService = new \Stripe\StripeClient(config('stripe.api_keys.secret_key'));
        }
    }

    public function createPaymentIntent($itemId, $amount = 1000, $currency = 'usd'): \Stripe\PaymentIntent
    {
        return $this->paymentService->paymentIntents->create([
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => [
                'item_id' => $itemId
            ]
        ]);
    }

    public function getPaymentIntent($paymentIntentId): \Stripe\PaymentIntent
    {
        return $this->paymentService->paymentIntents->retrieve($paymentIntentId);
    }
}
