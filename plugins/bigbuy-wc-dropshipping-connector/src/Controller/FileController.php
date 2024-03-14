<?php

namespace WcMipConnector\Controller;

defined('ABSPATH') || exit;

require_once __DIR__.'/../../vendor/autoload.php';

use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Manager\ImportProcessAttributeGroupManager;
use WcMipConnector\Manager\ImportProcessAttributeManager;
use WcMipConnector\Manager\ImportProcessBrandManager;
use WcMipConnector\Manager\ImportProcessCategoryManager;
use WcMipConnector\Manager\ImportProcessProductManager;
use WcMipConnector\Manager\ImportProcessTagManager;
use WcMipConnector\Manager\ImportProcessVariationManager;
use WcMipConnector\Manager\ZoneManager;
use WcMipConnector\Service\CacheService;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\FileLogService;
use WcMipConnector\Service\FileService;
use WcMipConnector\Service\LoggerService;
use WcMipConnector\Service\SystemService;
use WcMipConnector\Service\UnPublishProductService;

/**
 * Class FileController
 *
 * @package WcMipConnector\Controller
 */
class FileController
{
    public const MINIMUM_STORAGE_VALUE = 0.5;
    public const MAX_IMPORT_PROCESS_EXECUTION_MINUTES = 10;
    public const MAX_FILE_EXECUTION_MINUTES = 5;
    public const NON_VALUE = 0;
    public const READ_FILES_ROUTE = 'readfiles';
    public const READ_FILES_ENDPOINT = '/'.ApiController::ROOT_ENDPOINT.'/'.self::READ_FILES_ROUTE;

    /** @var LoggerService */
    protected $logger;

    /** @var SystemService */
    protected $systemService;

    /** @var FileLogManager */
    protected $fileLogManager;

    /** @var FileLogService */
    protected $fileLogService;

    /** @var FileService */
    protected $fileService;

    /** @var ImportProcessTagManager */
    protected $importProcessTagManager;

    /** @var ImportProcessProductManager */
    protected $importProcessProductManager;

    /** @var ImportProcessCategoryManager */
    protected $importProcessCategoryManager;

    /** @var ImportProcessAttributeGroupManager */
    protected $importProcessAttributeGroupManager;

    /** @var ImportProcessAttributeManager */
    protected $importProcessAttributeManager;

    /** @var ImportProcessBrandManager */
    protected $importProcessBrandManager;

    /** @var ImportProcessVariationManager */
    protected $importProcessVariationManager;

    /** @var DirectoryService */
    protected $directoryService;
    /** @var CacheService */
    private $cacheService;
    /** @var UnPublishProductService */
    private $unpublishProductService;
    /** @var ZoneManager */
    private $zoneManager;

    public function __construct()
    {
        $this->logger = new LoggerService;
        $this->systemService = new SystemService();
        $this->fileLogService = new FileLogService();
        $this->fileLogManager = new FileLogManager();
        $this->fileService = new FileService();
        $this->importProcessTagManager = new ImportProcessTagManager();
        $this->importProcessProductManager = new ImportProcessProductManager();
        $this->importProcessCategoryManager = new ImportProcessCategoryManager();
        $this->importProcessAttributeGroupManager = new ImportProcessAttributeGroupManager();
        $this->importProcessAttributeManager = new ImportProcessAttributeManager();
        $this->importProcessBrandManager = new ImportProcessBrandManager();
        $this->importProcessVariationManager = new ImportProcessVariationManager();
        $this->directoryService = new DirectoryService();
        $this->cacheService = new CacheService();
        $this->unpublishProductService = new UnPublishProductService();
        $this->zoneManager = new ZoneManager();
    }

    /**
     * @param array $queryVars
     * @return bool
     */
    public static function canHandleRequest(array $queryVars): bool
    {
        return \array_key_exists(ApiController::CRON_ROUTE, $queryVars) && ($queryVars[ApiController::CRON_ROUTE] === self::READ_FILES_ROUTE);
    }

    public function executeSynchronization(): bool
    {
        $checkSystemStorage = $this->systemService->getStorageServerInfo();

        if ($checkSystemStorage['Available'] < self::MINIMUM_STORAGE_VALUE) {
            $this->logger->getInstance()->error('There is not enough disk space. Space available (GB): '.$checkSystemStorage['Available']);

            return false;
        }

        $this->systemService->resetExecutionLimits();

        if (!$this->systemService->enablePlugin()) {
            $this->logger->getInstance()->error('Plugin is not active');

            return false;
        }

        try {
            $this->controlFileExecutionTime();
        } catch (\Exception $exception) {
            $this->logger->getInstance()->error('Error in ProcessFile - controlFileExecutionTime() , message: '.$exception->getMessage());

            return false;
        }

        require_once dirname(__DIR__, 2).'/WcMipConnectorCompatibility.php';

        try {
            wp_set_current_user(ConfigurationOptionManager::getUserId());

            $this->systemService->installWebHookUrl();
            $this->systemService->createCronExecutionLogFile();
            $this->fileLogService->checkOldFileLog();
            $this->fileLogService->checkIfOrphanFilesMustBeDeleted();
            $this->fileService->processFile();
            $this->unpublishProductService->purge();
            $this->cacheService->deleteRegisters();
            $this->zoneManager->disableLocalPickup(); // delete when there are no stores with a version lower than 1.9.6
        } catch (\Exception $exception) {
            $this->logger->getInstance()->error('Error in ProcessFile , message: '.$exception->getMessage());

            return false;
        } finally {
            wp_set_current_user(0);
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    private function controlFileExecutionTime(): void
    {
        $fileLocked = $this->fileLogManager->getFileIdInProcess();

        if (!$fileLocked) {
            return;
        }

        $fileProcessingStartDate = $fileLocked['date_processing_start'];
        $latestImportProcessDate = $this->getMaxImportProcessDateProcessed($fileLocked['file_id']);

        if ($latestImportProcessDate) {
            $dateNow = new \DateTime();
            $fileProcessingStartDateDiff = $dateNow->diff(new \DateTime($fileProcessingStartDate));
            $latestImportProcessDateDiff = $dateNow->diff(new \DateTime($latestImportProcessDate));

            $fileProcessingExecutionMinutes = filter_var($fileProcessingStartDateDiff->i, FILTER_SANITIZE_NUMBER_INT);
            $latestImportProcessExecutionMinutes = filter_var($latestImportProcessDateDiff->i, FILTER_SANITIZE_NUMBER_INT);

            if (
                $latestImportProcessDateDiff->h >= self::NON_VALUE
                && $fileProcessingExecutionMinutes > self::MAX_FILE_EXECUTION_MINUTES
                && $latestImportProcessExecutionMinutes > self::MAX_IMPORT_PROCESS_EXECUTION_MINUTES
            ) {
                $this->fileLogManager->resetFileState((int)$fileLocked['file_id']);
                $this->logger->getInstance()->error('Reset File ID: '.(int)$fileLocked['file_id']);
            }
        }
    }

    /**
     * @param int $fileId
     * @return string|null
     */
    public function getMaxImportProcessDateProcessed(int $fileId): ?string
    {
        $date = '2000-01-01 01:01:01';

        $maxDateProcessed[] = $this->importProcessTagManager->getMaxImportDateProcessed($fileId);
        $maxDateProcessed[] = $this->importProcessProductManager->getMaxImportDateProcessed($fileId);
        $maxDateProcessed[] = $this->importProcessCategoryManager->getMaxImportDateProcessed($fileId);
        $maxDateProcessed[] = $this->importProcessAttributeGroupManager->getMaxImportDateProcessed($fileId);
        $maxDateProcessed[] = $this->importProcessAttributeManager->getMaxImportDateProcessed($fileId);
        $maxDateProcessed[] = $this->importProcessBrandManager->getMaxImportDateProcessed($fileId);
        $maxDateProcessed[] = $this->importProcessVariationManager->getMaxImportDateProcessed($fileId);

        foreach ($maxDateProcessed as $maxDate) {
            if (\array_key_exists('date_update', $maxDate) && $date < $maxDate['date_update']) {
                $date = $maxDate['date_update'];
            }
        }

        return $date;
    }
}