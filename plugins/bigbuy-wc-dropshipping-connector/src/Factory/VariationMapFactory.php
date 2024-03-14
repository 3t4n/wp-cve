<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\VariationMap;

class VariationMapFactory
{
    /**
     * @param array $variation
     * @param int   $variationShopId
     *
     * @return VariationMap
     */
    public function create(array $variation, int $variationShopId): VariationMap
    {
        $variationMap = new VariationMap();
        $variationMap->variationId = $variation['VariationID'];
        $variationMap->shopVariationId = $variationShopId;

        return $variationMap;
    }
}