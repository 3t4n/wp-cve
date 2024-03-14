<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessBrandFactory;
use WcMipConnector\Manager\ImportProcessBrandPluginManager;

class ImportProcessBrandPluginService
{
    /** @var ImportProcessBrandPluginManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessBrandPluginManager();
    }

    /**
     * @param int $brandId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setSuccess(int $brandId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessBrandFactory();
        $importProcess = $importProcessFactory->create($brandId, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $brandId
     * @param int $fileId
     * @param bool $brandPlugin
     * @return bool
     * @throws \Exception
     */
    public function setFailure(int $brandId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessBrandFactory();
        $importProcess = $importProcessFactory->create($brandId, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param array $brandsIds
     * @param int $fileId
     * @return array
     */
    public function getProcessedBrands(array $brandsIds, int $fileId): array
    {
        if (empty($brandsIds)) {
            return [];
        }

        return $this->importProcessManager->getProcessedBrands($brandsIds, $fileId);
    }
}