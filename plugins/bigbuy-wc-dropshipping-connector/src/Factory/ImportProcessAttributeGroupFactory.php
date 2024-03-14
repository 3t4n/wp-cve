<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessAttributeGroup;
use WcMipConnector\Service\AttributeGroupService;

class ImportProcessAttributeGroupFactory
{
    /**
     * @param int    $attributeGroupId
     * @param int    $fileId
     * @param bool   $response
     *
     * @return ImportProcessAttributeGroup
     */
    public function create(int $attributeGroupId, int $fileId, bool $response): ImportProcessAttributeGroup
    {
        $importProcessFactory = new ImportProcessAttributeGroup();

        $importProcessFactory->attributeGroupMapId = $attributeGroupId;
        $importProcessFactory->fileId = $fileId;
        $importProcessFactory->response = $response;

        return $importProcessFactory;
    }
}