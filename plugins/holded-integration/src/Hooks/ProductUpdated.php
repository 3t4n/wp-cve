<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Hooks;

use Holded\Woocommerce\Services\ProductService;

final class ProductUpdated extends AbstractHook
{
    public function init(): void
    {
        add_action('woocommerce_update_product', [$this, 'updateProduct'], 10, 2);
    }

    /**
     * @param int         $productId
     * @param \WC_Product $productOld
     */
    public function updateProduct($productId, $productOld): void
    {
        (new ProductService($this->holdedSDK))->updateHoldedProduct($productId);
    }
}
