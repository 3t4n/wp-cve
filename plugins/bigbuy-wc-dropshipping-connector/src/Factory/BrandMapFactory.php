<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\BrandMap;

class BrandMapFactory
{
    /**
     * @param array    $brand
     * @param int|null $brandShopId
     *
     * @return BrandMap
     */
    public function create(array $brand, int $brandShopId = null): BrandMap
    {
        $brandMapModel = new BrandMap();
        $brandMapModel->brandId = $brand['BrandID'];
        $brandMapModel->shopBrandId = $brandShopId;
        $brandMapModel->version = $brand['Version'];

        return $brandMapModel;
    }
}