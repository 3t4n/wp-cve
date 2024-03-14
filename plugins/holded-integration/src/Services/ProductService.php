<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Services;

use Holded\SDK\DTOs\Product\Product;
use Holded\SDK\Loggers\ProductLogger;
use Holded\Woocommerce\Adapters\ProductAdapter;
use Holded\Woocommerce\Loggers\WoocommerceLogger;

class ProductService extends AbstractService
{
    public function updateHoldedProduct(int $productId): bool
    {
        $updated = false;

        $WCProduct = wc_get_product($productId);
        if ($WCProduct) {
            $product = ProductAdapter::fromWoocommerceToDTO($WCProduct);

            if (!$this->isValidProduct($product)) {
                return false;
            }

            if ((ProductLogger::getLogger())->getLastUpdatedProductSku() === $product->sku) {
                return false;
            }

            if ($product->kind === 'variants') {
                $product->removeVariantsWithoutSku();

                foreach ($product->variants ?? [] as $variant) {
                    if ((ProductLogger::getLogger())->getLastUpdatedProductSku() === $variant->sku) {
                        return false;
                    }
                }
            }

            $updated = $this->holdedSDK->updateProduct($product);
        }

        return $updated;
    }

    public function updateHoldedProductStock(\WC_Product $WCProduct): bool
    {
        $WCProduct = $this->getParentIfIsVariation($WCProduct);

        if (is_null($WCProduct)) {
            return false;
        }

        $product = ProductAdapter::fromWoocommerceToDTO($WCProduct);

        if ((ProductLogger::getLogger())->getLastUpdatedProductSku() === $product->sku) {
            return false;
        }

        if (!$this->isValidProduct($product)) {
            return false;
        }

        if ($product->kind === 'variants') {
            $product->removeVariantsWithoutSku();
        }

        return $this->holdedSDK->updateProductStock($product);
    }

    private function getParentIfIsVariation(\WC_Product $WCProduct): ?\WC_Product
    {
        if ($WCProduct->get_type() !== 'variation') {
            return $WCProduct;
        }

        $WCParentProduct = wc_get_product($WCProduct->get_parent_id());
        if (!$WCParentProduct) {
            (new WoocommerceLogger())->error(sprintf('Error getting parent product %s of product variation %s', $WCProduct->get_parent_id(), $WCProduct->get_id()));

            return null;
        }

        return $WCParentProduct;
    }

    private function isValidProduct(Product $product): bool
    {
        if ($product->kind === 'simple' && empty($product->sku)) {
            return false;
        }

        if ($product->kind === 'variants') {
            $product->removeVariantsWithoutSku();

            if (!$product->hasVariants()) {
                return false;
            }
        }

        return true;
    }
}
