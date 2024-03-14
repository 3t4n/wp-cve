<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ProductMap;

class ProductMapFactory
{
    private const RESET_VERSION = 0;

    /**
     * @param array $product
     * @param int   productShopId
     * @param string $languageIsoCode
     * @return ProductMap
     */
    public function create(array $product, int $productShopId, string $languageIsoCode): ProductMap
    {
        $productMapModel = new ProductMap();
        $productMapModel->productId = $product['ProductID'];
        $productMapModel->shopProductId = $productShopId;
        $productMapModel->imageVersion = $product['ImagesVersion'];
        $productMapModel->messageVersion = $product['MessageVersion'];

        foreach ($product['ProductLangs'] as $productLang) {
            if (strtolower($productLang['IsoCode']) === strtolower($languageIsoCode)) {
                $productMapModel->version = json_encode(
                    [
                        'version' => $product['Version'],
                        $productLang['IsoCode'] => $productLang['Version'],
                    ]
                );

                return $productMapModel;
            }
        }

        return $productMapModel;
    }

    /**
     * @param int $productId
     * @param int   productShopId
     *
     * @return ProductMap
     */
    public function createUnPublish(int $productId, int $productShopId): ProductMap
    {
        $productMapModel = new ProductMap();
        $productMapModel->productId = $productId;
        $productMapModel->shopProductId = $productShopId;
        $productMapModel->version = json_encode(
            [
                'version' => self::RESET_VERSION
            ]
        );

        return $productMapModel;
    }
}