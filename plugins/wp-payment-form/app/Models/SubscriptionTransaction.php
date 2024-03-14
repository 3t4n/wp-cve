<?php

namespace WPPayForm\App\Models;

if (!defined('ABSPATH')) {
    exit;
}

/**
 *  SubscriptionTransaction Model
 * @since 1.0.0
 */
class SubscriptionTransaction extends Model
{
    protected $table = 'wpf_order_transactions';

    public function createSubsTransaction($item)
    {
        $item['transaction_type'] = 'subscription';

        // checks when an offline subscription transaction creates
        if (isset($item['created_at']) && $item['created_at'] !== null) {
            $item['created_at'] = $item['created_at'];
            $item['updated_at'] = current_time('mysql');
        }
        if (!isset($item['created_at'])) {
            $item['created_at'] = current_time('mysql');
            $item['updated_at'] = current_time('mysql');
        }
        $createSubs = $this->create($item);
        return $createSubs->id;
    }

    public function maybeInsertCharge($item)
    {
        $exists = $this->where('transaction_type', 'subscription')
            ->where('submission_id', $item['submission_id'])
            ->where('subscription_id', $item['subscription_id'])
            ->where('charge_id', $item['charge_id'])
            ->where('payment_method', $item['payment_method'])
            ->first();

        if ($exists) {
            $this->updateSubsTransaction($exists->id, $item);
            return $exists->id;
        }
        $id = $this->createSubsTransaction($item);
        // We want to update the total amount here
        $parentSubscription = Subscription::where('id', $item['subscription_id'])
            ->first();

        // Let's count the total subscription payment
        if ($parentSubscription) {
            Subscription::where('id', $parentSubscription->id)
                ->update([
                    'bill_count' => $this->getPaymentCounts($parentSubscription->id),
                    'payment_total' => $this->getPaymentTotal($parentSubscription->id),
                    'updated_at' => current_time('mysql')
                ]);
        }

        return $id;
    }

    public function getSubscriptionTransactions($subscriptionId)
    {
        $transactions = $this->where('subscription_id', $subscriptionId)
            ->get();
        foreach ($transactions as $transaction) {
            $transaction->payment_note = maybe_unserialize($transaction->payment_note);
            $transaction->items = apply_filters('wppayform/subscription_items_' . $transaction->payment_method, [], $transaction);
        }
        return apply_filters('wppayform/subscription_transactions', $transactions, $subscriptionId);
    }

    public function getTransactions($submissionId)
    {
        $transactions = $this->where('submission_id', $submissionId)
            ->where('transaction_type', 'subscription')
            ->get();

        return apply_filters('wppayform/entry_subscription_transactions', $transactions, $submissionId);
    }

    public function hasSubscription($submissionId)
    {
        $transactions = $this->where('submission_id', $submissionId)
            ->where('transaction_type', 'subscription')
            ->get();

        return (bool) count($transactions) > 0;
    }

    public function getTransaction($transactionId)
    {
        return $this->where('id', $transactionId)
            ->where('transaction_type', 'subscription')
            ->first();
    }

    public function getLastSubscriptionTransaction($subscriptionId)
    {
        return $this->where('subscription_id', $subscriptionId)
            ->orderBy('id', 'DESC')
            ->first();
        ;
    }

    public function updateSubsTransaction($transactionId, $data)
    {
        $data['updated_at'] = current_time('mysql');
        $data['transaction_type'] = 'subscription';
        return $this->where('id', $transactionId)->update($data);
    }

    public function getPaymentCounts($subscriptionId, $paymentMethod = false)
    {
        $query = $this->select(['id'])
            ->where('transaction_type', 'subscription')
            ->where('subscription_id', $subscriptionId);
        if ($paymentMethod) {
            $query = $query->where('payment_method', $paymentMethod);
        }

        $totalPayments = $query->get();
        return count($totalPayments);
    }

    public function getPaymentTotal($subscriptionId, $paymentMethod = false)
    {
        $query = $this->select(['id', 'payment_total'])
            ->where('transaction_type', 'subscription')
            ->where('subscription_id', $subscriptionId);
        if ($paymentMethod) {
            $query = $query->where('payment_method', $paymentMethod);
        }
        $payments = $query->get();

        $paymentTotal = 0;

        foreach ($payments as $payment) {
            $paymentTotal += $payment->payment_total;
        }
        return $paymentTotal;
    }
}
