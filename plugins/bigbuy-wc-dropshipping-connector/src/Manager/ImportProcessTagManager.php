<?php

namespace WcMipConnector\Manager;

defined('ABSPATH') || exit;

use WcMipConnector\Entity\ImportProcessTag;
use WcMipConnector\Repository\ImportProcessTagRepository;

class ImportProcessTagManager
{
    /** @var ImportProcessTagRepository */
    private $importProcessRepository;

    public function __construct()
    {
        $this->importProcessRepository = new ImportProcessTagRepository();
    }

    /**
     * @param ImportProcessTag $importProcess
     *
     * @return bool
     * @throws \Exception
     */
    public function replace(ImportProcessTag $importProcess): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));

        $data = [
            'tag_id' => $importProcess->tagMapId,
            'file_id' => $importProcess->fileId,
            'response_api' => $importProcess->response,
            'date_add' => $dateNow->format('Y-m-d H:i:s'),
            'date_update' => $dateNow->format('Y-m-d H:i:s')
        ];

        return $this->importProcessRepository->replace($data);
    }

    /**
     * @param array $tagIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedTags(array $tagIds, int $fileId): array
    {
        return $this->importProcessRepository->getProcessedTags($tagIds, $fileId);
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