<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessAttributeGroupFactory;
use WcMipConnector\Manager\ImportProcessAttributeGroupManager;

class ImportProcessAttributeGroupService implements ImportProcessInterface
{
    /** @var ImportProcessAttributeGroupManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessAttributeGroupManager();
    }

    /**
     * @param int $attributeGroupId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setSuccess(int $attributeGroupId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessAttributeGroupFactory();
        $importProcess = $importProcessFactory->create($attributeGroupId, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $attributeGroupId
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setFailure(int $attributeGroupId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessAttributeGroupFactory();
        $importProcess = $importProcessFactory->create($attributeGroupId, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param array $attributesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedAttributesGroup(array $attributesIds, int $fileId): array
    {
        if (empty($attributesIds)) {
            return [];
        }

        return $this->importProcessManager->getProcessedAttributesGroup($attributesIds, $fileId);
    }
}