<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessTag;
use WcMipConnector\Service\TagService;

class ImportProcessTagFactory
{
    /**
     * @param int    $tagId
     * @param int    $fileId
     * @param bool $response
     *
     * @return ImportProcessTag
     */
    public function create(int $tagId, int $fileId, bool $response): ImportProcessTag
    {
        $importProcessFactory = new ImportProcessTag();

        $importProcessFactory->tagMapId = $tagId;
        $importProcessFactory->fileId = $fileId;
        $importProcessFactory->response = $response;

        return $importProcessFactory;
    }
}