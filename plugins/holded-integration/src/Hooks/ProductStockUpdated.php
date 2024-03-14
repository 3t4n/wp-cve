<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Hooks;

use Holded\Woocommerce\Services\ProductService;

final class ProductStockUpdated extends AbstractHook
{
    public function init(): void
    {
        add_action('woocommerce_variation_set_stock', [$this, 'updateProductStock'], 10, 2);
        add_action('woocommerce_product_set_stock', [$this, 'updateProductStock'], 10, 2);
    }

    /**
     * @param \WC_Product $productOld
     */
    public function updateProductStock($productOld): void
    {
        (new ProductService($this->holdedSDK))->updateHoldedProductStock($productOld);
    }
}
