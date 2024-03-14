<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessAttribute;
use WcMipConnector\Service\AttributeService;

class ImportProcessAttributeFactory
{
    /**
     * @param int    $attributeId
     * @param int    $fileId
     * @param bool   $response
     *
     * @return ImportProcessAttribute
     */
    public function create(int $attributeId, int $fileId, bool $response): ImportProcessAttribute
    {
        $importProcessFactory = new ImportProcessAttribute();

        $importProcessFactory->attributeMapId = $attributeId;
        $importProcessFactory->fileId = $fileId;
        $importProcessFactory->response = $response;

        return $importProcessFactory;
    }
}