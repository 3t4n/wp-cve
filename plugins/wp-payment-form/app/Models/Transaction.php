<?php

namespace WPPayForm\App\Models;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *  Transaction Model
 * @since 1.0.0
 */
class Transaction extends Model
{
    protected $table = 'wpf_order_transactions';

    public function createTransaction($item)
    {
        if (!isset($item['transaction_type'])) {
            $item['transaction_type'] = 'one_time';
        }

        return static::create($item);
    }

    public function getTransactions($submissionId)
    {
        $transactions = static::where('submission_id', $submissionId)
            ->where('transaction_type', 'one_time')
            ->get();

        $submission = Submission::select('payment_method')->where('id', $submissionId)->first();
        return apply_filters('wppayform/entry_transactions_' . $submission->payment_method, $transactions, $submissionId);
    }

    public function getTransaction($transactionId)
    {
        return static::where('id', $transactionId)
            ->where('transaction_type', 'one_time')
            ->first();
    }

    public function updateTransaction($transactionId, $data)
    {
        $data['updated_at'] = current_time('mysql');
        return static::where('id', $transactionId)->update($data);
    }

    public function getLatestTransaction($submissionId)
    {
        return static::where('submission_id', $submissionId)
            ->where('transaction_type', 'one_time')
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function getLatestIntentedTransaction($submissionId)
    {
        return static::where('submission_id', $submissionId)
            ->where('status', 'intented')
            ->where('transaction_type', 'one_time')
            ->orderBy('id', 'DESC')
            ->first();
    }
}
