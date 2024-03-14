<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessProductFactory;
use WcMipConnector\Manager\ImportProcessProductManager;

class ImportProcessProductService implements ImportProcessInterface
{
    /** @var ImportProcessProductManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessProductManager();
    }

    /**
     * @param int $productId
     * @param int $fileId
     *
     * @return bool
     * @throws \Exception
     */
    public function setSuccess(int $productId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessProductFactory();
        $importProcess = $importProcessFactory->create($productId, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $productId
     * @param int $fileId
     *
     * @return bool
     * @throws \Exception
     */
    public function setFailure(int $productId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessProductFactory();
        $importProcess = $importProcessFactory->create($productId, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param array $productIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedProducts(array $productIds, int $fileId): array
    {
        return $this->importProcessManager->getProcessedProducts($productIds, $fileId);
    }
}