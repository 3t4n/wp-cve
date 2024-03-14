<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Adapters;

use Holded\SDK\DTOs\Product\Option;
use Holded\SDK\DTOs\Product\Product;
use Holded\SDK\DTOs\Product\Variation;
use Holded\SDK\DTOs\Tax\Tax;
use Holded\Woocommerce\Services\ShopService;

final class ProductAdapter
{
    /**
     * @param \WC_Product|\WC_Product_Variation $product
     */
    private static function getPriceFromObject($product): string
    {
        return (string) wc_get_price_excluding_tax($product);
    }

    public static function fromWoocommerceToDTO(\WC_Product $woocommerceProduct): Product
    {
        $productData = $woocommerceProduct->get_data();
        $productId = $productData['id'];

        $productType = $woocommerceProduct->get_type();

        $baseCountry = (new \WC_Countries())->get_base_country();

        //Get taxes of the product
        $taxes = [];
        if (wc_tax_enabled() && get_option('woocommerce_tax_based_on') === 'base') {
            // 'base' is the way of woocommerce to say 'Main business address'
            $taxesThatWillBeApply = \WC_Tax::get_rates($woocommerceProduct->get_tax_class());
            $rawTaxes = \WC_Tax::get_rates_for_tax_class($woocommerceProduct->get_tax_class());

            foreach ($taxesThatWillBeApply as $key => $taxThatWillBeApply) {
                if (isset($rawTaxes[$key])) {
                    $rawTax = $rawTaxes[$key];

                    if ($rawTax->tax_rate_country === $baseCountry || $rawTax->tax_rate_country === '') {
                        $tax = new Tax();
                        $tax->type = Tax::TYPE_PERCENTAGE;
                        $tax->name = $rawTax->tax_rate_name;
                        $tax->country = $rawTax->tax_rate_country;
                        $tax->rate = $rawTax->tax_rate;
                        $tax->origin = [
                            'provider'   => ShopService::getProviderName(),
                            'providerId' => ShopService::getShopUrl(),
                            'originType' => 'tax',
                            'originId'   => $rawTax->tax_rate_id,
                        ];

                        $taxes[] = $tax;

                        // Only send one tax
                        break;
                    }
                }
            }
        }

        $product = new Product();
        $product->kind = 'simple';
        $product->provider = ShopService::getProviderName();
        $product->shopUrl = ShopService::getShopUrl();
        $product->name = $productData['name'];
        $product->description = $productData['description'];
        $product->taxes = $taxes;
        $product->price = self::getPriceFromObject($woocommerceProduct);
        $product->barcode = $productData['barcode'] ?? '';
        $product->sku = trim($productData['sku']);
        $product->weight = $productData['weight'];
        $product->stock = $woocommerceProduct->get_stock_quantity();
        $product->forSale = true;
        $product->forPurchase = true;
        $product->origin = [
            'provider'   => ShopService::getProviderName(),
            'providerId' => ShopService::getShopUrl(),
            'originType' => $productType,
            'originId'   => $productId,
        ];

        if ($productType === 'variable' && $woocommerceProduct instanceof \WC_Product_Variable) {
            $product->kind = 'variants';
            $product->variants = array_map(function (array $attributeCombination) use ($productId) {
                $variationId = $attributeCombination['variation_id'];
                $variationObj = new \WC_Product_Variation($variationId);
                $stock = $variationObj->get_stock_quantity();

                $variation = new Variation();
                $variation->barcode = $attributeCombination['barcode'] ?? '';
                $variation->sku = trim($attributeCombination['sku']);
                $variation->price = self::getPriceFromObject($variationObj);
                $variation->stock = ($variationObj->get_manage_stock()) ? $stock : 0;

                if (!empty($variationObj->get_variation_attributes(false))) {
                    $variation->options = [];
                    foreach ($variationObj->get_variation_attributes(false) as $parentName => $value) {
                        $option = new Option();
                        $option->parentId = $parentName.$productId;
                        $option->parentName = $parentName;
                        $option->id = $value;
                        $option->value = $value;

                        $variation->options[] = $option;
                    }
                }

                $variation->cost = self::getCost($variationId);

                return $variation;
            }, $woocommerceProduct->get_available_variations());
        }

        $product->options = [];
        if (!empty($product->variants)) {
            $allOptions = array_map(function (Variation $variant) {
                return $variant->options ?? [];
            }, $product->variants);

            $product->options = array_merge(...$allOptions);
        }

        $imageId = $woocommerceProduct->get_image_id();
        $imageUrl = wp_get_attachment_image_url($imageId, 'full');
        $product->imageUrl = $imageUrl;

        $product->cost = self::getCost($productData['id']);

        return $product;
    }

    /**
     * @param float|int|string|null $id
     *
     * @return string|null
     */
    private static function getCost($id)
    {
        $plugin = 'cost-of-goods-for-woocommerce/cost-of-goods-for-woocommerce.php';
        if (
            !in_array($plugin, (array) get_option('active_plugins', []), true) ||
            !class_exists('Alg_WC_Cost_of_Goods_Products')
        ) {
            return null;
        }

        $cost = (new \Alg_WC_Cost_of_Goods_Products())->get_product_cost($id);

        return is_numeric($cost) ? (string) $cost : null;
    }
}
