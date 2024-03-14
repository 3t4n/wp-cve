<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessAttribute;
use WcMipConnector\Repository\ImportProcessAttributeRepository;

class ImportProcessAttributeManager
{
    /** @var ImportProcessAttributeRepository */
    private $importProcessAttributeRepository;

    public function __construct()
    {
        $this->importProcessAttributeRepository = new ImportProcessAttributeRepository();
    }

    /**
     * @param ImportProcessAttribute $importProcess
     *
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessAttribute $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        $data = [
            'attribute_id' => $importProcess->attributeMapId,
            'file_id' => $importProcess->fileId,
            'response_api' => $importProcess->response,
            'date_add' => $dateNow->format('Y-m-d H:i:s'),
            'date_update' => $dateNow->format('Y-m-d H:i:s')
        ];

        return $this->importProcessAttributeRepository->replace($data);
    }

    /**
     * @param array $attributesIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedAttributes(array $attributesIds, int $fileId): array
    {
        return $this->importProcessAttributeRepository->getProcessedAttributes($attributesIds, $fileId);
    }

    /**
     * @return int
     */
    public function countWithError(): int
    {
        return $this->importProcessAttributeRepository->countWithError();
    }

    /**
     * @param int $fileId
     * @return array
     */
    public function getMaxImportDateProcessed(int $fileId): array
    {
        return $this->importProcessAttributeRepository->getMaxImportDateProcessed($fileId);
    }
}