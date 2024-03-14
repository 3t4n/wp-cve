<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessAttributeFactory;
use WcMipConnector\Manager\ImportProcessAttributeManager;

class ImportProcessAttributeService implements ImportProcessInterface
{
    /** @var ImportProcessAttributeManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessAttributeManager();
    }

    /**
     * @param int $attributeId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setSuccess(int $attributeId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessAttributeFactory();
        $importProcess = $importProcessFactory->create($attributeId, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $attributeId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setFailure(int $attributeId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessAttributeFactory();
        $importProcess = $importProcessFactory->create($attributeId, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    public function setBatchFailure(array $attributeIds, int $fileId)
    {
        foreach ($attributeIds as $attributeId)
        {
            $this->setFailure($attributeId, $fileId);
        }
    }

    /**
     * @param array $attributesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedAttributes(array $attributesIds, int $fileId): array
    {
        if (empty($attributesIds)) {
            return [];
        }

        return $this->importProcessManager->getProcessedAttributes($attributesIds, $fileId);
    }
}