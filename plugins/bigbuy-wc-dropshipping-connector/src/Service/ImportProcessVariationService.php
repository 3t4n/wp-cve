<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Enum\ImportProcessResponse;
use WcMipConnector\Factory\ImportProcessVariationFactory;
use WcMipConnector\Manager\ImportProcessVariationManager;

class ImportProcessVariationService implements ImportProcessInterface
{
    /** @var ImportProcessVariationManager */
    protected $importProcessManager;

    public function __construct()
    {
        $this->importProcessManager = new ImportProcessVariationManager();
    }

    /**
     * @param int $variationId
     * @param int $fileId
     *
     * @return bool
     */
    public function setSuccess(int $variationId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessVariationFactory();
        $importProcess = $importProcessFactory->create($variationId, $fileId, ImportProcessResponse::SUCCESS_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }

    /**
     * @param int $variationId
     * @param int $fileId
     *
     * @return bool
     */
    public function setFailure(int $variationId, int $fileId): bool
    {
        $importProcessFactory = new ImportProcessVariationFactory();
        $importProcess = $importProcessFactory->create($variationId, $fileId, ImportProcessResponse::FAIL_PROCESS);

        return $this->importProcessManager->replace($importProcess);
    }
}