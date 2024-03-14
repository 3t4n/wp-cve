<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\WCBrand;
use WcMipConnector\Service\DirectoryService;

class BrandPluginFactory
{
    public const BRAND_PLUGIN_PREFIX = 'brandp_';
    public const BRAND_DEFAULT_IMAGE = 'https://cdnbigbuy.com/images/brand/default_mini.png';

    /** @var DirectoryService|null  */
    protected $directoryService;

    /**
     * BrandPluginFactory constructor.
     * @param DirectoryService|null $directoryService
     */
    public function __construct(
        ?DirectoryService $directoryService = null
    ) {
        $this->directoryService = $directoryService;

        if (!$directoryService) {
            $this->directoryService = new DirectoryService();
        }
    }

    /**
     * @param array $brandData
     * @return WCBrand
     */
    public function create(array $brandData, bool $isBrandMapped): WCBrand
    {
        $brandModel = new WCBrand();
        $brandModel->name = $brandData['BrandName'];
        $brandModel->slug = strtolower(self::BRAND_PLUGIN_PREFIX.$brandData['BrandID']);

        if ($isBrandMapped) {
            $brandModel->image = null;

            return $brandModel;
        }

        $imageResult = $this->directoryService->fileRemoteExist((string)$brandData['ImageURL']);
        $image = $imageResult ? $brandData['ImageURL'] : self::BRAND_DEFAULT_IMAGE;
        $brandModel->image = ['src' => $image];

        return $brandModel;
    }
}