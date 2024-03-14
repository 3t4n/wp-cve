<?php
declare(strict_types=1);

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCProduct;

class UnPublishFactory
{
    private const DISABLED_PRODUCT = 'private';
    private const CATALOG_VISIBILITY = 'hidden';

    /**
     * @param array $productData
     * @param int   $productShopId
     *
     * @return WCProduct
     */
    public function create(array $productData, int $productShopId): WCProduct
    {
        $productModel = new WCProduct();
        $productModel->id = $productShopId;
        $productModel->stock_quantity = $productData['Stock'];
        $productModel->status = self::DISABLED_PRODUCT;
        $productModel->catalog_visibility = self::CATALOG_VISIBILITY;

        return $productModel;
    }
}