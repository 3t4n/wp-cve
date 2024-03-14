<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Hooks;

use Holded\Woocommerce\Services\OrderService;

final class OrderCompleted extends AbstractHook
{
    public function init(): void
    {
        add_action('woocommerce_order_status_completed', [$this, 'orderCompleted']);
        add_action('woocommerce_order_status_pending', [$this, 'orderCompleted']);
        add_action('woocommerce_order_status_failed', [$this, 'orderCompleted']);
        add_action('woocommerce_order_status_processing', [$this, 'orderCompleted']);
        add_action('woocommerce_order_status_refunded', [$this, 'orderCompleted']);
        add_action('woocommerce_order_status_cancelled', [$this, 'orderCompleted']);
        add_action('woocommerce_order_status_on-hold', [$this, 'orderCompleted']);
    }

    /**
     * @param int $orderId
     */
    public function orderCompleted($orderId): void
    {
        (new OrderService($this->holdedSDK))->updateHoldedInvoice($orderId);
    }
}
