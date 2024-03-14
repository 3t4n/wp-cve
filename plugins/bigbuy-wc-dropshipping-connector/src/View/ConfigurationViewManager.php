<?php

namespace WcMipConnector\View;

defined('ABSPATH') || exit;

use WcMipConnector\Controller\FileController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Exception\WooCommerceApiExceptionInterface;
use WcMipConnector\Factory\AccountInfoReportFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\LanguageReportManager;
use WcMipConnector\Model\View\ConfigurationView;
use WcMipConnector\Service\AccountInfoReportService;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\SystemService;
use WcMipConnector\Service\TaxesService;

class ConfigurationViewManager
{
    private const DEFAULT_ISO_CODE = 'en';
    private const RECOMMENDED_RAM_FREE = 512;

    /** @var AccountInfoReportService */
    protected $accountInfoReportService;

    /** @var SystemService */
    protected $systemService;

    /** @var TaxesService */
    private $taxesService;

    /** @var LanguageReportManager */
    private $languageManager;

    /** @var DirectoryService */
    private $directoryService;

    /**
     * ConfigurationViewManager constructor.
     * @param SystemService|null $systemService
     */
    public function __construct(?SystemService $systemService = null)
    {
        $this->accountInfoReportService = new AccountInfoReportService();

        if ($systemService) {
            $this->systemService = $systemService;
        } else {
            $this->systemService = new SystemService();
        }

        $this->taxesService = new TaxesService();
        $this->languageManager = new LanguageReportManager();
        $this->directoryService = new DirectoryService();
    }

    /**
     * @return ConfigurationView
     * @throws \Exception
     */
    public function create(): ConfigurationView
    {
        $configurationView = new ConfigurationView();

        $configurationView->accountReport = $this->accountInfoReportService->getWoocommerceReport();
        $configurationView->memoryInfo = $this->systemService->getMemoryInfo();
        $configurationView->taxes = $this->taxesService->getTaxes();
        $configurationView->defaultIsoCode = $this->getLanguageIsoCode();
        $configurationView->storageServerInfo = $this->systemService->getStorageServerInfo();
        $lastCronExecutionDate = AccountInfoReportFactory::DEFAULT_CRON_EXECUTION_TIME;

        if ($configurationView->accountReport) {
            $lastCronExecutionDate = $configurationView->accountReport->LastCronExecutionDate;
        }

        $configurationView->cron = $this->systemService->checkIfCronIsActive($lastCronExecutionDate);
        $configurationView->systemStorage = $this->systemService->getStorageServerInfo();
        $configurationView->disabledRequiredFunctions = $this->systemService->getRequiredDisabledFunctions();
        $configurationView->warningMessages = $this->getWarningMessages(
            $configurationView->systemStorage,
            $configurationView->memoryInfo,
            $configurationView->disabledRequiredFunctions
        );

        return $configurationView;
    }

    /**
     * @return string
     */
    public function getLanguageIsoCode(): string
    {
        try {
            $language = explode('_', $this->languageManager->getDefaultLanguageIsoCode());
        } catch (WooCommerceApiExceptionInterface $e) {
            return self::DEFAULT_ISO_CODE;
        }

        return $language[0];
    }

    /**
     * @param array $systemStorage
     * @param array $memoryInfo
     * @param array $disabledRequiredFunctions
     * @return array
     */
    public function getWarningMessages(array $systemStorage = [], array $memoryInfo = [], array $disabledRequiredFunctions = []): array
    {
        $warningMessages = [];

        if (isset($_GET['page']) && sanitize_text_field($_GET['page']) === MipWcConnector::MODULE_NAME && (get_option('woocommerce_version') < MipWcConnector::WC_VERSION)) {
            $warningMessages['WcVersion'] = esc_html__('BigBuy Dropshipping Connector for WooCommerce is not compatible with your version of Woocommerce. Please ', 'WC-Mipconnector');
        }

        $storageAvailable = $systemStorage['Available'] ?? null;

        if ($storageAvailable && $storageAvailable < FileController::MINIMUM_STORAGE_VALUE) {
            $warningMessages['storage_almost_full'] = esc_html__('Your disk storage is almost full. We have blocked the creation of products so that your server continues to operate, free up space or expand your disk to prevent its synchronization from being affected.', 'WC-Mipconnector');
        } elseif ($storageAvailable === 0) {
            $warningMessages['storage_full'] = esc_html__('Your disk storage was full. You do not have enough space to continue creating and synchronizing products. Free up space or expand your disk so you can resume syncing your store.', 'WC-Mipconnector');
        }

        $memoryFree = $memoryInfo['MemFree'] ?? null;

        if ($memoryFree !== null && $memoryFree < self::RECOMMENDED_RAM_FREE) {
            $warningMessages['low_performance'] = esc_html__('Your server is performing poorly, you should review it and improve resources for the proper functioning of your store. Contact your server for more information.', 'WC-Mipconnector');
        }

        try {
            $this->directoryService->checkPathOwnership($this->directoryService->getModuleDir());
        } catch (\Exception $exception) {
            $warningMessages['folder_ownership_mismatch'] = esc_html__('The user owner of the plugin directory content is not the same as the user that is executing the process. Use CHOWN accordingly for fix this issue.', 'WC-Mipconnector');
        }

        if ($this->systemService->isApache() && !$this->systemService->isModRewriteActive()) {
            $warningMessages['mod_rewrite_not_active'] = esc_html__('Enable the Apache mod_rewrite module.', 'WC-Mipconnector');
        }

        if (!ConfigurationOptionManager::existsPermalink()) {
            $warningMessages['permalink_not_active'] = esc_html__('The user-friendly URL option for your WooCommerce should be activated.', 'WC-Mipconnector');
        }

        if (!empty($disabledRequiredFunctions)) {
            $disabledFunctions = \implode(", ", $disabledRequiredFunctions);
            $warningMessages['disabled_required_functions'] = sprintf(esc_html__('Your server has disabled the following functions: %s', 'WC-Mipconnector'), $disabledFunctions);
        }

        if (PHP_VERSION < MipWcConnector::PHP_MIN_VERSION_SUPPORT) {
            $warningMessages['php_support'] = esc_html__('From June 2023 we will no longer support and synchronise with PHP 7.2. The minimum supported version will be PHP 7.4, but PHP 8.0 is recommended.', 'WC-Mipconnector');
        }

        return $warningMessages;
    }
}