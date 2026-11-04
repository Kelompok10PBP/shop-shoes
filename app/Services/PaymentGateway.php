<?php

namespace App\Services;

use App\Models\Order;

interface PaymentGateway
{
    /**
     * Process payment for an order
     *
     * @param Order $order
     * @param array $payload
     * @return array ['success' => bool, 'transaction_id' => string, 'message' => string]
     */
    public function charge(Order $order, array $payload = []): array;
}
