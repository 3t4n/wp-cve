<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCDimension;
use WcMipConnector\Entity\WCVariation;

class VariationFactory
{
    private const PUBLISH_STATUS = 'publish';
    private const VISIBILITY_STATUS = 'visible';

    /**
     * @param array $variationData
     * @param array $attributes
     *
     * @return WCVariation
     */
    public function create(array $variationData = null, array $attributes = null): WCVariation
    {
        $variation = new WCVariation();

        $variation->sku = $variationData['SKU'];
        $variation->price = $variationData['Price'];
        $variation->regular_price = (string)$variationData['Price'];
        $variation->sale_price = (string)$variationData['Price'];

        if ($variationData['Stock'] > 0) {
            $variation->in_stock = true;
        }

        $variation->manage_stock = true;
        $variation->stock_quantity = $variationData['Stock'];
        $variation->weight = (string)$variationData['Weight'];

        $dimension = new WCDimension();
        $dimension->length = (string)$variationData['Length'];
        $dimension->width = (string)$variationData['Width'];
        $dimension->height = (string)$variationData['Height'];

        $variation->status = self::PUBLISH_STATUS;
        $variation->catalog_visibility = self::VISIBILITY_STATUS;

        $variation->dimensions = $dimension;

        foreach ($attributes as $attribute) {
            $variation->attributes[] = ['id' => $attribute['id'], 'option' => $attribute['name']];
        }

        return $variation;
    }

    /**
     * @param int $productStock
     *
     * @return int
     */
    private function checkStock(int $productStock): int
    {
        if ($productStock > StockFactory::MAXIMUM_AVAILABLE_STOCK) {
            return StockFactory::MAXIMUM_AVAILABLE_STOCK;
        }

        return $productStock;
    }
}