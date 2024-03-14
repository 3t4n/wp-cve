<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

class ProductUrlFactory
{
    /**
     * @param array $productUrls
     * @param string $isoCode
     * @return array
     */
    public function create(array $productUrls, string $isoCode): array
    {
        $products = [];

        foreach ($productUrls as $productUrl) {
            $uniqueKey = $productUrl['product_shop_id'].'-'.$productUrl['variation_shop_id'];
            $products[$uniqueKey]['ProductID'] = $productUrl['product_shop_id'];
            $products[$uniqueKey]['Reference'] = $productUrl['sku'];
            $products[$uniqueKey]['ProductUrls'][] = [
                'IsoCode' => $isoCode,
                'Link' => $productUrl['url'],
                'Version' => 1,
                'AttributeID' => $productUrl['variation_shop_id'],
            ];
        }

        return $products;
    }

    /**
     * @param array $variationUrls
     * @param string $isoCode
     * @param array $variations
     * @return array
     */
    public function createVariation(array $variationUrls, string $isoCode, array $variations): array
    {
        foreach ($variationUrls as $variationUrl) {
            $uniqueKey = $variationUrl['product_shop_id'].'-'.$variationUrl['variation_shop_id'];
            $variations[$uniqueKey]['ProductID'] = $variationUrl['product_shop_id'];
            $variations[$uniqueKey]['Reference'] = $variationUrl['sku'];
            $variations[$uniqueKey]['ProductUrls'][] = [
                'IsoCode' => $isoCode,
                'Link' => $variationUrl['url'],
                'Version' => 1,
                'AttributeID' => $variationUrl['variation_shop_id'],
            ];
        }

        return $variations;
    }
}