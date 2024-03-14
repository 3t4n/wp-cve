<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessTagFactory;
use WcMipConnector\Manager\ImportProcessTagManager;

class ImportProcessTagService implements ImportProcessInterface
{
    /** @var ImportProcessTagManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessTagManager();
    }

    /**
     * @param int $id
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setSuccess(int $id, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessTagFactory();
        $importProcess = $importProcessFactory->create($id, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $id
     * @param int $fileId
     * @return bool
     * @throws \Exception
     */
    public function setFailure(int $id, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessTagFactory();
        $importProcess = $importProcessFactory->create($id, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param array $tagIds
     * @param int   $fileId
     *
     * @return array
     */
    public function getProcessedTags(array $tagIds, int $fileId): array
    {
        if (empty($tagIds)) {
            return [];
        }

        return $this->importProcessManager->getProcessedTags($tagIds, $fileId);
    }
}