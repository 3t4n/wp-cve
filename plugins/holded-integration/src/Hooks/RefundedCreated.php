<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Hooks;

final class RefundedCreated extends AbstractHook
{
    public function init(): void
    {
        add_action('woocommerce_refund_created', [$this, 'createRefund'], 10, 2);
    }

    /**
     * @param int     $refundId
     * @param mixed[] $args
     */
    public function createRefund($refundId, $args): void
    {
    }
}
