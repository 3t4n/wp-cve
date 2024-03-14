<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessBrand;
use WcMipConnector\Service\BrandService;

class ImportProcessBrandFactory
{
    /**
     * @param int    $brandId
     * @param int    $fileId
     * @param bool   $response
     *
     * @return ImportProcessBrand
     */
    public function create(int $brandId, int $fileId, bool $response): ImportProcessBrand
    {
        $importProcessFactory = new ImportProcessBrand();

        $importProcessFactory->brandMapId = $brandId;
        $importProcessFactory->fileId = $fileId;
        $importProcessFactory->response = $response;

        return $importProcessFactory;
    }
}