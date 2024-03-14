<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCBrand;

class BrandFactory
{
    public const BRAND_PREFIX = 'brand_';

    /**
     * @param array $brandData
     * @param bool $brandPlugin
     * @return WCBrand
     */
    public function create(array $brandData): WCBrand
    {
        $brandModel = new WCBrand();
        $brandModel->name = $brandData['BrandName'];
        $brandModel->slug = strtolower(self::BRAND_PREFIX.$brandData['BrandID']);

        return $brandModel;
    }
}