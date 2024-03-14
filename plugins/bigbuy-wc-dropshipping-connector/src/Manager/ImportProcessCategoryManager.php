<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessCategory;
use WcMipConnector\Repository\ImportProcessCategoryRepository;

class ImportProcessCategoryManager
{
    /** @var ImportProcessCategoryRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessCategoryRepository();
    }

    /**
     * @param ImportProcessCategory $importProcess
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessCategory $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));
        $data = [
            'category_id' => $importProcess->categoryMapId,
            'file_id' => $importProcess->fileId,
            'response_api' => $importProcess->response,
            'date_add' => $dateNow->format('Y-m-d H:i:s'),
            'date_update' => $dateNow->format('Y-m-d H:i:s'),
        ];

        return $this->importProcessRepository->replace($data);
    }

    /**
     * @param array $categoryIds
     * @return int
     */
    public function deleteByCategoryIds(array $categoryIds): int
    {
        $categoryIdList = implode(',', $categoryIds);

        return $this->importProcessRepository->deleteByCategoryIds($categoryIdList);
    }

    /**
     * @param array $categoriesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedCategories(array $categoriesIds, int $fileId): array
    {
        return $this->importProcessRepository->getProcessedCategories($categoriesIds, $fileId);
    }

    /**
     * @return int
     */
    public function countWithError(): int
    {
        return $this->importProcessRepository->countWithError();
    }

    /**
     * @param int $fileId
     * @return array
     */
    public function getMaxImportDateProcessed(int $fileId): array
    {
        return $this->importProcessRepository->getMaxImportDateProcessed($fileId);
    }
}