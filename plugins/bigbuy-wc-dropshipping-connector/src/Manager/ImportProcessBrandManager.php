<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessBrand;
use WcMipConnector\Repository\ImportProcessBrandRepository;

class ImportProcessBrandManager
{
    /** @var ImportProcessBrandRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessBrandRepository();
    }

    /**
     * @param ImportProcessBrand $importProcess
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessBrand $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        $data = [
            'brand_id' => $importProcess->brandMapId,
            'file_id' => $importProcess->fileId,
            'response_api' => $importProcess->response,
            'date_add' => $dateNow->format('Y-m-d H:i:s'),
            'date_update' => $dateNow->format('Y-m-d H:i:s'),
        ];

        return $this->importProcessRepository->replace($data);
    }

    /**
     * @param array $brandsIds
     * @param int $fileId
     * @return array
     */
    public function getProcessedBrands(array $brandsIds, int $fileId): array
    {
        return $this->importProcessRepository->getProcessedBrands($brandsIds, $fileId);
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