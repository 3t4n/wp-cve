<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessBrand;
use WcMipConnector\Repository\ImportProcessBrandPluginRepository;

class ImportProcessBrandPluginManager
{
    /** @var ImportProcessBrandPluginRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessBrandPluginRepository();
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
}