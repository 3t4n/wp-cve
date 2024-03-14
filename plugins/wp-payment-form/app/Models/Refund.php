<?php

namespace WPPayForm\App\Models;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *  Refund Model
 * @since 2.0.0
 */
class Refund extends Model
{
    protected $table = 'wpf_order_transactions';

    public function createRefund($item)
    {
        $item['transaction_type'] = 'refund';

        return $this->create($item);
    }

    public function getRefunds($submissionId)
    {
        $refunds = $this->where('submission_id', $submissionId)
            ->where('transaction_type', 'refund')
            ->get();

        return apply_filters('wppayform/entry_refunds', $refunds, $submissionId);
    }

    public function getRefundTotal($submissionId)
    {
        $refunds = $this->select(['id', 'payment_total'])
            ->where('submission_id', $submissionId)
            ->where('transaction_type', 'refund')
            ->get();

        $refundTotal = 0;
        foreach ($refunds as $refund) {
            $refundTotal += $refund->payment_total;
        }

        return $refundTotal;
    }

    public function getRefund($refundId)
    {
        return $this->where('id', $refundId)
            ->where('transaction_type', 'refund')
            ->first();
    }

    public function updateRefund($refundId, $data)
    {
        $data['updated_at'] = current_time('mysql');
        return $this->where('id', $refundId)
            ->update($data);
    }

    public function getLatestRefund($submissionId)
    {
        return $this->where('submission_id', $submissionId)
            ->where('transaction_type', 'refund')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getRefundByChargeId($chargeId)
    {
        return $this->where('charge_id', $chargeId)
            ->where('transaction_type', 'refund')
            ->first();
    }
}
