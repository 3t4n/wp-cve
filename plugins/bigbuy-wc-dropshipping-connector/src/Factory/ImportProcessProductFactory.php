<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessProduct;

class ImportProcessProductFactory
{
    /**
     * @param int    $productId
     * @param int    $fileId
     * @param bool   $response
     *
     * @return ImportProcessProduct
     */
    public function create(int $productId, int $fileId, bool $response): ImportProcessProduct
    {
        $importProcessFactory = new ImportProcessProduct();

        $importProcessFactory->productMapId = $productId;
        $importProcessFactory->fileId = $fileId;
        $importProcessFactory->response = $response;

        return $importProcessFactory;
    }
}