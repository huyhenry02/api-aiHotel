<?php

namespace App\Modules\Payment\Services;

use Stripe\PaymentIntent;
use Stripe\StripeClient;

class PaymentService
{
    protected $paymentService;

    public function __construct()
    {
        if (!$this->paymentService) {
            $this->paymentService = new StripeClient(config('stripe.api_keys.secret_key'));
        }
    }

    public function createPaymentIntent($itemId, $amount = 1000, $currency = 'usd'): PaymentIntent
    {
        return $this->paymentService->paymentIntents->create([
            'amount' => $amount,
            'currency' => $currency,
            'metadata' => [
                'item_id' => $itemId
            ]
        ]);
    }

    public function getPaymentIntent($paymentIntentId): PaymentIntent
    {
        return $this->paymentService->paymentIntents->retrieve($paymentIntentId);
    }
}
