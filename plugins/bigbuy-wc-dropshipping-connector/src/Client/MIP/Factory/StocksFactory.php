<?php
/**
 * @author    BigBuy
 * @copyright 2010-2020 BigBuy
 * @license   license
 */

namespace WcMipConnector\Client\MIP\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Client\MIP\Model\Stock;

class StocksFactory
{
    /** @var StocksFactory */
    private static $instance;

    /**
     * @return StocksFactory
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param array $stocksApi
     * @return Stock[]
     */
    public function create($stocksApi)
    {
        $stocks = [];

        foreach ($stocksApi as $stockApi) {
            $stock = new Stock();
            $stock->productActive = (bool)$stockApi['productActive'];
            $stock->productId = $stockApi['productId'];
            $stock->productVariationId = $stockApi['productVariationId'];
            $stock->stockQuantity = (int)$stockApi['stockQuantity'];

            $stocks[] = $stock;
        }

        return $stocks;
    }
}
