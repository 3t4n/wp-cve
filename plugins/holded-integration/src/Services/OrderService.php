<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Services;

use Holded\Woocommerce\Adapters\OrderAdapter;

class OrderService extends AbstractService
{
    public function updateHoldedInvoice(int $orderId): bool
    {
        $WCOrder = wc_get_order($orderId);
        if (!$WCOrder) {
            return false;
        }

        try {
            $order = OrderAdapter::fromWoocommerceToDTO($WCOrder);

            // Create / Update salesorder.
            $holdedInvoiceId = $WCOrder->get_meta('_holdedwc_invoice_id');
            if (empty($holdedInvoiceId)) {
                $this->holdedSDK->syncOrder($order);

            // TODO: Implement async calls to the plugin in order to save this ID
                //if (is_string($result)) {
                //    update_post_meta($orderId, '_holdedwc_invoice_id', $result);
                //
                //    $result = true;
                //}
            } else {
                $this->holdedSDK->syncOrder($order);
            }

            return true;
        } catch (\Exception $e) {
        }

        return false;
    }
}
