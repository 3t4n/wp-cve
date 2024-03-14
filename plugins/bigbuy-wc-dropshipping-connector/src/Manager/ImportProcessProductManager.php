<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessProduct;
use WcMipConnector\Repository\ImportProcessProductRepository;

class ImportProcessProductManager
{
    /** @var ImportProcessProductRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessProductRepository();
    }

    /**
     * @param array $productIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedProducts(array $productIds, int $fileId): array
    {
        if (empty($productIds)) {
            return [];
        }

        return $this->importProcessRepository->getProcessedProducts($productIds, $fileId);
    }

    /**
     * @param ImportProcessProduct $importProcess
     *
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessProduct $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));
        $data = [
            'product_id' => $importProcess->productMapId,
            'file_id' => $importProcess->fileId,
            'response_api' => $importProcess->response,
            'date_add' => $dateNow->format('Y-m-d H:i:s'),
            'date_update' => $dateNow->format('Y-m-d H:i:s'),
        ];

        return $this->importProcessRepository->replace($data);
    }

    /**
     * @param int $fileId
     * @return array
     */
    public function getMaxImportDateProcessed(int $fileId): array
    {
        return $this->importProcessRepository->getMaxImportDateProcessed($fileId);
    }

    /**
     * @return int
     */
    public function countWithError(): int
    {
        return $this->importProcessRepository->countWithError();
    }

    /**
     * @param string $fileName
     * @return array|null
     */
    public function getImportProcessInfo(string $fileName): ?array
    {
        return $this->importProcessRepository->getImportProcessInfo($fileName);
    }


}