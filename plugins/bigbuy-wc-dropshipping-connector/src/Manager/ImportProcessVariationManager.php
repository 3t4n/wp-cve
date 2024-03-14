<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessVariation;
use WcMipConnector\Repository\ImportProcessVariationRepository;

class ImportProcessVariationManager
{
    /** @var ImportProcessVariationRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessVariationRepository();
    }

    /**
     * @param ImportProcessVariation $importProcess
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessVariation $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        $data = [
            'variation_id' => $importProcess->variationMapId,
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
     * @param array $variationIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedVariations(array $variationIds, int $fileId): array
    {
        if (empty($variationIds)) {
            return [];
        }

        return $this->importProcessRepository->getProcessedVariations($variationIds, $fileId);
    }
}