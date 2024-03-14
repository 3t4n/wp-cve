<?php

namespace WcMipConnector\Factory;

use WcMipConnector\Entity\WCProduct;

defined('ABSPATH') || exit;

class StockFactory
{
    public const MAXIMUM_AVAILABLE_STOCK = 10;

    /**
     * @param int $productShopId
     * @param int $productStockQuantity
     * @return WCProduct
     */
    public function create(int $productShopId, int $productStockQuantity): WCProduct
    {
        $productModel = new WCProduct();
        $productModel->id = $productShopId;
        $productModel->stock_quantity = $productStockQuantity;
        $productModel->in_stock = $productStockQuantity > 0;

        return $productModel;
    }

    /**
     * @param int $variationShopId
     * @param array $variation
     * @param string $variationSku
     * @return WCProduct
     */
    public function createVariation(int $variationShopId, array $variation, string $variationSku): WCProduct
    {
        $productModel = new WCProduct();
        $productModel->id = $variationShopId;
        $productModel->stock_quantity = $this->checkStock($variation['stock']);
        $productModel->in_stock = $variation['stock'] > 0;
        $productModel->sku = $variationSku;

        return $productModel;
    }

    /**
     * @param int $productStockQuantity
     * @return int
     */
    private function checkStock(int $productStockQuantity): int
    {
        if ($productStockQuantity > self::MAXIMUM_AVAILABLE_STOCK) {
            return self::MAXIMUM_AVAILABLE_STOCK;
        }

        return $productStockQuantity;
    }
}