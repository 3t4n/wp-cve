<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Client\Base\Exception\ClientErrorException;
use WcMipConnector\Client\BigBuy\Service\ApiService;
use WcMipConnector\Client\MIP\Customer\Service\SellingChannelConnectorService;
use WcMipConnector\Controller\OrderController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Enum\StatusTypes;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\SystemManager;

class SystemService
{
    private const SQL_UPGRADE_FILE = 'sql-upgrade-';
    private const UPGRADE_FILE = 'upgrade-%version%.php';
    private const FACTOR_GB_TO_MB = 1024.0;
    private const FACTOR_KB_TO_MB = 0.001;
    private const FACTOR_B_TO_MB = 0.000001;
    private const MIN_MEMORY_LIMIT = 1024;
    private const MIN_TIME_LIMIT = 15000;
    private const KB_VALUE = 1024;
    private const KB_MEDIUM_VALUE = 512;
    private const KB_LOW_VALUE = 256;
    private const NON_VALUE = 0;
    private const MEMORY_INFO_PATH = '/proc/meminfo';
    private const TOTAL_MEMORY = 'MemTotal';
    private const FREE_MEMORY = 'MemFree';
    private const BATCH_BY_DEFAULT = 25;
    private const MEDIUM_BATCH_VALUE = 20;
    private const LOW_BATCH_VALUE = 15;
    private const LOWEST_BATCH_VALUE = 10;
    private const MINIMUM_BATCH_VALUE = 5;
    private const FLOCK_DIR = '/usr/bin/flock';
    private const CRON_EVERY_MINUTE = '* * * * *';
    private const CRON_EVERY_DAY = '15 0 * * *';
    private const FLOCK_CRON = '/usr/bin/flock -n';
    private const CURL_CRON = '/usr/bin/curl';
    private const WOOCOMMERCE_CRON = '/tmp/wc_process_import_';
    private const GOOGLE_SHOPPING_CRON = '/tmp/gs_process_import_';
    private const REQUIRED_FUNCTION_NAMES = ['shell_exec', 'disk_free_space', 'disk_total_space', 'file_get_contents', 'glob', 'getmyuid', 'fileowner'];
    private const URL_SYSTEM_LANGUAGES = 'wcmipconnector/api?messageType=SYSTEM&operationType=LANGUAGES';

    /** @var SystemManager  */
    protected $systemManager;
    /** @var LoggerService */
    protected $loggerService;
    /** @var DirectoryService */
    private $directoryService;
    /** @var SellingChannelConnectorService  */
    private $sellingChannelConnectorService;

    public function __construct(
        ?SystemManager $systemManager = null,
        ?LoggerService $loggerService = null,
        ?DirectoryService $directoryService = null,
        ?string $accessToken = null,
        ?SellingChannelConnectorService $sellingChannelConnectorService = null
    ) {
        $this->systemManager = $systemManager ?? new SystemManager();
        $this->loggerService = $loggerService ?? new LoggerService();
        $this->directoryService = $directoryService ?? DirectoryService::getInstance();
        $accessToken = $accessToken ?? ConfigurationOptionManager::getAccessToken();
        $this->sellingChannelConnectorService = $sellingChannelConnectorService ?? SellingChannelConnectorService::getInstance($accessToken);
    }

    /**
     * @throws \Exception
     */
    public function installMipConnector(): void
    {
        $this->loadDatabaseSchema();
        $this->setDefaultConfiguration();
        $this->installWebHookUrl();
    }

    public function setDefaultConfiguration()
    {
        $this->setFolderPermissions();
        $this->systemManager->createWcMipConnectorOptionsIfNotExists();
    }

    public function setFolderPermissions(): void
    {
        try {
            $uploadDir = $this->directoryService->getUploadDir();
            $this->directoryService->createDirectory($uploadDir);
            $this->directoryService->setFolderPermissionRecursively($uploadDir, 0777);
        } catch (\Exception $exception) {
            $this->loggerService->getInstance()->critical($exception->getMessage());
        }

        try {
            $logDir = $this->directoryService->getLogDir();
            $this->directoryService->createDirectory($logDir);
        } catch (\Exception $exception) {
            $this->loggerService->getInstance()->critical($exception->getMessage());
        }

        try {
            $importFilesDir = $this->directoryService->getImportFilesDir();
            $this->directoryService->createDirectory($importFilesDir);
        } catch (\Exception $exception) {
            $this->loggerService->getInstance()->critical($exception->getMessage());
        }

        $this->directoryService->createHtaccessIfNotExists();
    }

    public function checkFileFolderPermissions(): bool
    {
        $this->setFolderPermissions();
        $logDir = $this->directoryService->getLogDir();
        $importFilesDir = $this->directoryService->getImportFilesDir();
        $uploadsDir = $this->directoryService->getUploadDir();

        return @is_writable($logDir) && @is_readable($logDir) &&
            @is_writable($importFilesDir) && @is_readable($importFilesDir) &&
            @is_writable($uploadsDir) && @is_readable($uploadsDir);
    }

    /**
     * @param string $fileVersion
     * @return bool
     */
    public function checkIfPluginNeedUpgrade(string $fileVersion): bool
    {
        if (!$this->isNeedUpdateModule($fileVersion)) {
            return true;
        }

        if (!$this->upgradeModuleFiles()) {
            return false;
        }

        if (!$this->loadDatabaseSchema()) {
            return false;
        }

        $this->setDefaultConfiguration();

        return true;
    }

    /**
     * @param string $fileVersion
     *
     * @return bool
     */
    private function isNeedUpdateModule(string $fileVersion): bool
    {
        return version_compare(ConfigurationOptionManager::getPluginFilesVersion(), ConfigurationOptionManager::getPluginDatabaseVersion(), '>') ||
            version_compare($fileVersion, ConfigurationOptionManager::getPluginDatabaseVersion(), '>') ||
            version_compare($fileVersion, ConfigurationOptionManager::getPluginFilesVersion(), '>');
    }

    /**
     * @return bool
     */
    public function setManageStockOption(): bool
    {
        return ConfigurationOptionManager::setManageStockOption();
    }

    /**
     * @param string $newVersion
     * @param string $currentVersion
     * @return bool
     */
    public function shouldSkipUpdateModuleFilesByVersion(string $newVersion, string $currentVersion): bool
    {
        return version_compare($newVersion, $currentVersion, '<=');
    }

    /**
     * @return bool
     */
    public function upgradeModuleFiles(): bool
    {
        try {
//            $this->directoryService->checkPathOwnership(get_current_user(), $this->directoryService->getModuleDir());
            $uploadDir = $this->directoryService->getPluginsDir();
            $this->directoryService->setFolderPermissionRecursively($uploadDir);

            $connector = $this->sellingChannelConnectorService->getSellingChannelConnector(2);
            $connectorUrl = $connector->url;
            $connectorVersion = $connector->version;
            $moduleVersion = ConfigurationOptionManager::getPluginFilesVersion();

            if ($this->shouldSkipUpdateModuleFilesByVersion($connectorVersion, $moduleVersion)) {
                $this->loggerService->info('Version compare', [$connectorVersion, $moduleVersion]);

                return true;
            }

            $updateContent = $this->directoryService->getFileContent($connectorUrl);

            if (!$updateContent) {
                $this->loggerService->error('File upload without content '.$connectorUrl);

                return false;
            }

            $nameZipTmp = 'tmpUpdateModule.zip';
            $moduleDir = $this->directoryService->getModuleDir();
            $this->directoryService->saveFileContent($nameZipTmp, $moduleDir, $updateContent);
            $this->directoryService->initializeWPFilesystem();

            $result = $this->directoryService->unzipFile($moduleDir .'/'.$nameZipTmp, $this->directoryService->getPluginsDir());

            if ($this->directoryService->isWPError($result)) {
                $this->loggerService->error('Unzip file - Code: '.$result->get_error_code().' - Error Data: '.$result->get_error_data());

                return false;
            }

            $this->directoryService->deleteFile($moduleDir .'/'.$nameZipTmp);
            $this->loggerService->info('Plugin updated to the version '.$connectorVersion);

            return true;
        } catch (ClientErrorException $exception) {
            $this->loggerService->error(__METHOD__.' Client Error '.$exception->getMessage());

            return false;
        } catch (\Exception $exception) {
            $this->loggerService->critical('Updating the plugin ' . $exception->getMessage());

            return false;
        }
    }

    public function loadDatabaseSchema(): bool
    {
        $this->systemManager->createWcMipConnectorTables();

        return $this->loadUpgradeSqlFromFiles();
    }

    /**
     * @return bool
     */
    public function loadUpgradeSqlFromFiles(): bool
    {
        $moduleVersion = ConfigurationOptionManager::getPluginDatabaseVersion();

        $upgradeFiles = $this->getUpgradeSqlFilesOrderByVersion();

        if (empty($upgradeFiles)) {
            return true;
        }

        foreach ($upgradeFiles as $upgradeFile) {
            $versionUpgrade = $this->getVersionFromSqlUpgradeFileName($upgradeFile);

            if ($this->shouldSkipUpdateModuleFilesByVersion($versionUpgrade, $moduleVersion)) {
                continue;
            }

            $sql = require $this->directoryService->getUpdateSqlDir().'/'.$upgradeFile;
            if (!empty($sql) && is_array($sql)) {
                foreach ($sql as $query) {
                    $this->systemManager->executeSql($query);
                }
            }

            $this->loadUpgradeFileByVersion($versionUpgrade);
            ConfigurationOptionManager::setPluginDatabaseVersion($versionUpgrade);
        }

        ConfigurationOptionManager::setPluginDatabaseVersion(ConfigurationOptionManager::getPluginFilesVersion());

        return true;
    }

    public function getUpgradeSqlFilesOrderByVersion(): array
    {
        $updateSqlDir = $this->directoryService->getUpdateSqlDir().'/';

        if (!file_exists($updateSqlDir) || !($files = scandir($updateSqlDir))) {
            return [];
        }

        foreach ($files as $file) {
            if ($this->isSqlUpgradeFile($file)) {
                $updateSqlFiles[] = $file;
            }
        }

        usort($updateSqlFiles, 'version_compare');

        return $updateSqlFiles;
    }

    public function loadUpgradeFileByVersion($version)
    {
        $fileName = str_replace('%version%', $version, self::UPGRADE_FILE);

        if (!file_exists($this->directoryService->getUpdateDir().'/'.$fileName)){
            return null;
        }

        return require $this->directoryService->getUpdateDir().'/'.$fileName;
    }

    /**
     * @param string $file
     * @return bool
     */
    private function isSqlUpgradeFile(string $file): bool
    {
        return substr($file, -4) === '.php' && substr($file, 0, 12) === self::SQL_UPGRADE_FILE;
    }

    /**
     * @param string $file
     * @return string
     */
    private function getVersionFromSqlUpgradeFileName(string $file): string
    {
        return str_replace(['.php' , self::SQL_UPGRADE_FILE],[''],$file);
    }

    public function resetExecutionLimits(): void
    {
        $maxExecutionTime = @ini_get('max_execution_time');
        if ((int)$maxExecutionTime > 0 && (int)$maxExecutionTime < self::MIN_TIME_LIMIT) {
            set_time_limit(self::MIN_TIME_LIMIT);
        }

        $memoryLimit = $this->convertShorthandBytesToMegabytes(@ini_get('memory_limit'));
        if ($memoryLimit > 0 && $memoryLimit < self::MIN_MEMORY_LIMIT) {
            ini_set('memory_limit', self::MIN_MEMORY_LIMIT.'M');
        }
    }

    /**
     * @return bool
     */
    public function enablePlugin(): bool
    {
        if (ConfigurationOptionManager::isPluginEnable()) {
            return true;
        }

        return ConfigurationOptionManager::enablePlugin();
    }

    /**
     * @param string $shorthandValue
     * @return int
     */
    private function convertShorthandBytesToMegabytes(string $shorthandValue): int
    {
        $sanitizedShorthandValue = mb_strtolower(trim($shorthandValue));
        $value = (int)$sanitizedShorthandValue;

        if (false !== mb_strpos($sanitizedShorthandValue, 'm')) {
            return $value;
        }

        if (false !== mb_strpos($sanitizedShorthandValue, 'g')) {
            return (int)($value * self::FACTOR_GB_TO_MB);
        }

        if (false !== strpos($sanitizedShorthandValue, 'k')) {
            return (int)($value * self::FACTOR_KB_TO_MB);
        }

        return (int)($value * self::FACTOR_B_TO_MB);
    }

    public function getMemoryInfo(): array
    {
        $memoryInfo = [
            self::TOTAL_MEMORY => 0,
            self::FREE_MEMORY => 0
        ] ;

        try {
            return $this->getMemoryByProcInfo($memoryInfo);
        } catch (\Throwable $e) {
            $this->loggerService->info(__METHOD__.' getMemoryByProcInfo '.$e->getMessage());
        }

        try {
            return $this->getMemoryByFreeCommand($memoryInfo);
        } catch (\Throwable $e) {
            $this->loggerService->info(__METHOD__.' getMemoryByFreeCommand '.$e->getMessage());
        }

        return $memoryInfo;
    }

    /**
     * @param array $memoryInfo
     * @return array
     * @throws \Exception
     */
    private function getMemoryByProcInfo(array $memoryInfo): array
    {
        if (!@is_readable('/proc/meminfo')) {
            throw new \Exception('Proc mem info not readable');
        }

        $memoryInfoData = explode("\n", file_get_contents(self::MEMORY_INFO_PATH));

        foreach ($memoryInfoData as $line) {
            $key = strtok($line, ':');

            if ($key === self::TOTAL_MEMORY || $key === self::FREE_MEMORY) {
                $memoryInfo[$key] = (int)(filter_var($line, FILTER_SANITIZE_NUMBER_INT) / self::KB_VALUE);
            }
        }

        return $memoryInfo;
    }

    /**
     * @param array $memoryInfo
     * @return array
     * @throws \Exception
     */
    private function getMemoryByFreeCommand(array $memoryInfo): array
    {
        if (!@is_callable('exec')) {
            throw new \Exception('Exec command not callable');
        }

        $memoryInfo[self::TOTAL_MEMORY] = (int)\exec("free -mtl | awk 'FNR == 2 {print $2}'");
        $memoryInfo[self::FREE_MEMORY] = (int)\exec("free -mtl | awk 'FNR == 2 {print $4}'");

        return $memoryInfo;
    }

    /**
     * @return int
     */
    public function setBatchValue(): int
    {
        $memoryInfo = $this->getMemoryInfo();

        if (!$memoryInfo[self::FREE_MEMORY]) {
            return self::LOWEST_BATCH_VALUE;
        }

        if ($memoryInfo[self::FREE_MEMORY] === self::NON_VALUE) {
            return self::MINIMUM_BATCH_VALUE;
        }

        if ($memoryInfo[self::FREE_MEMORY] <= self::KB_LOW_VALUE) {
            return self::LOW_BATCH_VALUE;
        }

        if ($memoryInfo[self::FREE_MEMORY] <= self::KB_MEDIUM_VALUE) {
            return self::MEDIUM_BATCH_VALUE;
        }

        return self::BATCH_BY_DEFAULT;
    }

    /**
     * @return int
     */
    public function getBatchValue(): int
    {
        $batchValue = $this->setBatchValue();
        $this->loggerService->info('Batch value: '.$batchValue);

        return $batchValue;
    }

    /**
     * @return array
     */
    public function getStorageServerInfo(): array
    {
        $dataStorage['Total'] = 0;
        $dataStorage['Available'] = 0;

        if (!$this->functionCanBeInvoked('disk_total_space')) {
            $dataStorage['Total'] = self::bytesToGigabytes(\disk_total_space($this->directoryService->getModuleDir()));
        }

        if (!$this->functionCanBeInvoked('disk_free_space')) {
            $dataStorage['Available'] = self::bytesToGigabytes(\disk_free_space($this->directoryService->getModuleDir()));
        }

        return $dataStorage;
    }

    /**
     * @param float|null $bytes
     * @return float|null
     */
    public static function bytesToGigabytes(float $bytes = null): ?float
    {
        return round($bytes / 1024 / 1024 / 1024, 2);
    }

    /**
     * @param float $bytes
     * @return float
     */
    public static function bytesToMegabytes(float $bytes): float
    {
        return round($bytes / 1024 / 1024, 2);
    }

    /**
     * @param float $kiloBytes
     * @return float
     */
    public static function kiloBytesToMegabytes(float $kiloBytes): float
    {
        return round($kiloBytes / 1024 , 2);
    }

    /**
     * @return bool
     */
    public function checkFLockExists(): bool
    {
        if (@file_exists(self::FLOCK_DIR)) {
            return true;
        }

        return false;
    }

    /**
     * @param string $lastCronExecutionDate
     * @return bool
     * @throws \Exception
     */
    public function checkIfCronIsActive(string $lastCronExecutionDate): bool
    {
        $dateNow = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateCron = new \DateTime($lastCronExecutionDate, new \DateTimeZone('UTC'));
        $intervalDates = $dateNow->diff($dateCron);

        return $intervalDates->days < 1;
    }

    /**
     * @return string
     */
    public function defineSshCronTask(): string
    {
        if ($this->checkFLockExists()) {
            return self::CRON_EVERY_MINUTE.' '.self::FLOCK_CRON.' '.self::WOOCOMMERCE_CRON. parse_url(ConfigurationOptionManager::getOptionBySiteUrl(), PHP_URL_HOST) .'.lockfile '.self::CURL_CRON;
        }

        return self::CRON_EVERY_MINUTE.' '.self::CURL_CRON;
    }

    /**
     * @return string
     */
    public function defineGsCronTask(): string
    {
        if ($this->checkFLockExists()) {
            return self::CRON_EVERY_DAY.' '.self::FLOCK_CRON.' '.self::GOOGLE_SHOPPING_CRON. parse_url(ConfigurationOptionManager::getOptionBySiteUrl(), PHP_URL_HOST) .'.lockfile '.self::CURL_CRON;
        }

        return self::CRON_EVERY_DAY.' '.self::CURL_CRON;
    }

    public function installWebHookUrl(): void
    {
        $orderAddHook = $this->systemManager->findWebHookByName('MIP_ORDER_ADD');

        if (
            !$orderAddHook
			|| (
                \array_key_exists('webhook_id', $orderAddHook)
				&& $orderAddHook['delivery_url'] !== get_home_url().OrderController::ORDER_CONTROLLER_URL
            )
        ) {
            $this->systemManager->installWebHook('MIP_ORDER_ADD', 'order.created',OrderController::ORDER_CONTROLLER_URL, $orderAddHook['webhook_id']);
        }

        $orderUpdateHook = $this->systemManager->findWebHookByName('MIP_ORDER_UPDATE');

        if (
            !$orderUpdateHook
	        || (
                \array_key_exists('webhook_id', $orderUpdateHook)
				&& $orderUpdateHook['delivery_url'] !== get_home_url().OrderController::ORDER_CONTROLLER_URL
            )
        ) {
            $this->systemManager->installWebHook('MIP_ORDER_UPDATE', 'order.updated',OrderController::ORDER_CONTROLLER_URL, $orderUpdateHook['webhook_id']);
        }
    }

    public function findCronExecutionLogFileDate(): ?\DateTime
    {
        if (!$this->directoryService->fileExist(MipWcConnector::CRON_EXECUTION_LOG_FILENAME, $this->directoryService->getLogDir())) {
            return null;
        }

        $timezone = new \DateTimeZone('UTC');

        $cronExecutionLogContent = $this->directoryService->getFileContent(MipWcConnector::CRON_EXECUTION_LOG_FILENAME, $this->directoryService->getLogDir());

        if ($cronExecutionLogContent === false) {
            return null;
        }

        try {
            $cronExecutionLogFileDate = \DateTime::createFromFormat(DATE_W3C, $cronExecutionLogContent, $timezone);
        } catch (\Exception $exception) {
            $this->loggerService->info(__METHOD__.' Could not retrieve cron execution log date from file content '.$exception->getMessage());

            return null;
        }

        if ($cronExecutionLogFileDate === false) {
            return null;
        }

        return $cronExecutionLogFileDate;
    }

    /**
     * @return string|null
     */
    public function getCronExecutionDate(): ?string
    {
        $executionLogDateTime = $this->findCronExecutionLogFileDate();

        return $executionLogDateTime ? $executionLogDateTime->format(DATE_W3C) : null;
    }

    /**
     * @throws \Exception
     */
    public function createCronExecutionLogFile():void
    {
        $timezone = new \DateTimeZone('UTC');
        $currentDate = new \DateTime();
        $currentDate->setTimezone($timezone);
        $this->directoryService->saveFileContent(
            MipWcConnector::CRON_EXECUTION_LOG_FILENAME,
            $this->directoryService->getLogDir(),
            $currentDate->format(DATE_W3C)
        );
    }

    /**
     * @return bool
     */
    public function isApache(): bool
    {
        return isset($_SERVER['SERVER_SOFTWARE'])
            && stripos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false;
    }

    public function isModRewriteActive(): bool
    {
        if (\function_exists('apache_get_modules') && \in_array('mod_rewrite', apache_get_modules())) {
            return true;
        }

        return $this->isModRewriteConfigured();
    }

    private function isModRewriteConfigured(): bool
    {
        $requestUrl = $this->getShopUrl().self::URL_SYSTEM_LANGUAGES;

        try {
            $response = $this->getModRewriteTestUrlResponse($requestUrl);
        } catch (\Throwable $exception) {
            $this->loggerService->warning('Exception in checkRequestResponse(): '.$exception->getMessage());

            return false;
        }

        $responseBody = \json_decode($response, true);

        if (
            \is_array($responseBody)
            && \array_key_exists('Code', $responseBody)
            && \array_key_exists('Message', $responseBody)
            && $responseBody['Code'] === StatusTypes::HTTP_BAD_REQUEST
            && $responseBody['Message'] === SecurityService::ACCESS_TOKEN_MESSAGE_ERROR
        ) {
            return true;
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    private function getModRewriteTestUrlResponse(string $url): string
    {
        $statusCode = null;
        $ch = null;

        try {
            $ch = \curl_init($url);

            \curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

            \curl_setopt($ch, CURLOPT_POST, 1);

            \curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = \curl_exec($ch);
            $statusCode = (int)\curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } catch (\Throwable $exception) {
            $this->loggerService->warning('Exception in checkRequestResponse(): '.$exception->getMessage());
        }

        \curl_close($ch);

        if (
            empty($response)
            || $statusCode !== StatusTypes::HTTP_OK
        ) {
            throw new \Exception('Incorrect curl response');
        }

        return $response;
    }

    public function getShopUrl(): string
    {
        $siteUrl = \rtrim(get_site_url(), '/');

        return $siteUrl.'/';
    }

    /**
     * @param string $urlFile
     * @return string
     */
    public function convertUrlToDirectory(string $urlFile): string
    {
        $shopUrl = $this->getShopUrl();
        $filePath = \str_replace($shopUrl, '', $urlFile);
        $rootPath = $this->directoryService->getRootDir();

        return $rootPath . $filePath;
    }

    /**
     * @param string $directoryPath
     * @return string
     */
    public function convertDirectoryToUrl(string $directoryPath): string
    {
        $rootPath = $this->directoryService->getRootDir();
        $filePath = \str_replace($rootPath, '', $directoryPath);
        $shopUrl = $this->getShopUrl();

        return $shopUrl . $filePath;
    }


    public function getRequiredDisabledFunctions(): array
    {
        $requiredDisabledFunctions = [];

        foreach (self::REQUIRED_FUNCTION_NAMES as $functionName) {
            if ($this->functionCanBeInvoked($functionName)) {
                $requiredDisabledFunctions[] = $functionName;
            }
        }

        return $requiredDisabledFunctions;
    }

    private function functionCanBeInvoked(string $name): bool
    {
        $disabledFunctions = (string)@\ini_get('disable_functions');

        return !\function_exists($name) || false !== \stripos($disabledFunctions, $name);
    }

    public function isValidApiKey(string $apiKey): bool
    {
        $modulePlatforms = ApiService::getInstance($apiKey)->getModulePlatforms();

        return !empty($modulePlatforms);
    }
}