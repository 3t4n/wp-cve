<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessVariation;

class ImportProcessVariationFactory
{
    /**
     * @param int    $variationId
     * @param int    $fileId
     * @param bool   $response
     *
     * @return ImportProcessVariation
     */
    public function create(int $variationId, int $fileId, bool $response): ImportProcessVariation
    {
        $importProcessVariation = new ImportProcessVariation();
        $importProcessVariation->variationMapId = $variationId;
        $importProcessVariation->fileId = $fileId;
        $importProcessVariation->response = $response;

        return $importProcessVariation;
    }
}