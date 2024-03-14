<?php

namespace ZahlsPaymentGateway\Util;

use Zahls\Models\Response\Gateway;
use Zahls\Models\Response\Transaction;

class StatusUtil
{

    /**
     * @param Gateway $gateway
     * @param array $gateway
     * @return int
     */
    public static function getAmountByStatusAndGateway(Gateway $gateway, array $status): int
    {
        $amount = 0;
        foreach ($gateway->getInvoices() as $invoice) {
            foreach($invoice['transactions'] as $transaction) {
                if (!in_array($transaction['status'], $status)) continue;
                $amount += $transaction['amount'];
            }
        }
        return $amount;
    }

    /**
     * @param int $orderTotal
     * @param int $confirmedAmount
     * @param int $refundedAmount
     * @return string
     */
    public static function determineNewOrderStatus($orderTotal_input, $confirmedAmount, $refundedAmount)
    {
      	$orderTotal = $orderTotal_input * 100;
        $paidAmount = $confirmedAmount + $refundedAmount;

        if (strval($paidAmount) == strval($orderTotal)){ error_log("confirmed"); return Transaction::CONFIRMED; }
        if ($confirmedAmount == abs($refundedAmount) && $confirmedAmount > 0){ return Transaction::REFUNDED; }
        if ($confirmedAmount > abs($refundedAmount) && abs($refundedAmount) > 0) { return Transaction::PARTIALLY_REFUNDED; }
        if ($confirmedAmount < $orderTotal){ return Transaction::WAITING; } // Partially paid
    }
}