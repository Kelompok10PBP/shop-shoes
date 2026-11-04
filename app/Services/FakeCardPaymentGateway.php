<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class FakeCardPaymentGateway implements PaymentGateway
{
    public function charge(Order $order, array $payload = []): array
    {
        // Simulate immediate successful processing for demo
        $txnId = 'CARD-' . strtoupper(Str::random(12));

        return [
            'success' => true,
            'transaction_id' => $txnId,
            'message' => 'Card charged successfully via 256-bit secure gateway.',
        ];
    }
}
