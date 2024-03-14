<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

class ProductImageUrlFactory
{
    /**
     * @param array $productImageUrls
     * @param array $products
     * @return array
     */
    public function create(array $productImageUrls, array $products): array
    {
        foreach ($productImageUrls as $productImageUrl) {
            $uniqueKey = $productImageUrl['product_shop_id'].'-0';

            $products[$uniqueKey]['ProductID'] = $productImageUrl['product_shop_id'];
            $products[$uniqueKey]['Reference'] = $productImageUrl['sku'];

            $products[$uniqueKey]['Images'][] = [
                'ImageURL' => $productImageUrl['url'],
                'Order' => 1,
                'Cover' => $productImageUrl['cover'],
            ];
        }

        return $products;
    }
}