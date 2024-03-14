<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessCategory;
use WcMipConnector\Service\CategoryService;

class ImportProcessCategoryFactory
{
    /**
     * @param int    $categoryId
     * @param int    $fileId
     * @param bool $response
     *
     * @return ImportProcessCategory
     */
    public function create(int $categoryId, int $fileId, bool $response): ImportProcessCategory
    {
        $importProcessFactory = new ImportProcessCategory();

        $importProcessFactory->categoryMapId = $categoryId;
        $importProcessFactory->fileId = $fileId;
        $importProcessFactory->response = $response;

        return $importProcessFactory;
    }
}