<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessCategoryFactory;
use WcMipConnector\Manager\ImportProcessCategoryManager;

class ImportProcessCategoryService implements ImportProcessInterface
{
    /** @var ImportProcessCategoryManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessCategoryManager();
    }

    /**
     * @param int $categoryId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setSuccess(int $categoryId, int $fileId): bool
    {
        $importProcessProductFactory = new ImportProcessCategoryFactory();
        $importProcess = $importProcessProductFactory->create($categoryId, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $categoryId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setFailure(int $categoryId, int $fileId): bool
    {
        $importProcessProductFactory = new ImportProcessCategoryFactory();
        $importProcess = $importProcessProductFactory->create($categoryId, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param array $categoriesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedCategories(array $categoriesIds, int $fileId): array
    {
        if (empty($categoriesIds)) {
            return [];
        }

        return $this->importProcessManager->getProcessedCategories($categoriesIds, $fileId);
    }
}