<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessAttributeGroup;
use WcMipConnector\Repository\ImportProcessAttributeGroupRepository;

class ImportProcessAttributeGroupManager
{
    /** @var ImportProcessAttributeGroupRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessAttributeGroupRepository();
    }

    /**
     * @param ImportProcessAttributeGroup $importProcess
     *
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessAttributeGroup $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));
        $data = [
            'attribute_group_id' => $importProcess->attributeGroupMapId,
            'file_id' => $importProcess->fileId,
            'response_api' => $importProcess->response,
            'date_add' => $dateNow->format('Y-m-d H:i:s'),
            'date_update' => $dateNow->format('Y-m-d H:i:s')
        ];

        return $this->importProcessRepository->replace($data);
    }

    /**
     * @param array $attributesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedAttributesGroup(array $attributesIds, int $fileId): array
    {
        return $this->importProcessRepository->getProcessedAttributesGroup($attributesIds, $fileId);
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