<?php

namespace ZahlsPaymentGateway\Service;

class SubscriptionService
{
    /**
     * @var ZahlsApiService
     */
    protected $zahlsApiService;

    public function __construct($zahlsApiService)
    {
        $this->zahlsApiService = $zahlsApiService;
    }

    /**
     * @param float $amount
     * @param \WC_Order $renewal
     */
    public function process_recurring_payment($amount, $renewal)
    {
        $subscriptions = wcs_get_subscriptions_for_order($renewal, array('order_type' => 'any'));

        foreach ($subscriptions as $subscription) {

			
							if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
					// HPOS usage is enabled.
				} else {
					  // Get tokenization from the subscription (new way)
            $tokenizationId = intval(get_post_meta($subscription->get_id(), 'zahls_auth_transaction_id', true));
				}
          

            if (!$tokenizationId) {
                // Get tokenization id from the last order except this renewal (old way)
                $related_orders = $subscription->get_related_orders('ids');

                foreach ($related_orders as $order_id) {
                    if ($order_id === $renewal->get_id()) continue;
                    $last_order_id = $order_id;
                    break;
                }
                $tokenizationId = intval(get_post_meta($last_order_id, 'zahls_auth_transaction_id', true));

                $subscription->update_meta_data('zahls_auth_transaction_id', $tokenizationId);
                $subscription->save();
            }

            // Both must be given to do a valid recurring transaction
            if ($this->zahlsApiService->chargeTransaction($tokenizationId, $amount)) {
                continue;
            }

            // Recurring payment failed if we reach this point
            $subscription->payment_failed();
        }
    }
}