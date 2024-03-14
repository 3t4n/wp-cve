<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Controller\FileController;
use WcMipConnector\Enum\MipWcConnector;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Model\AccountInfoReportModel;
use WcMipConnector\Model\WoocommerceReportModel;
use WcMipConnector\Service\DirectoryService;
use WcMipConnector\Service\SystemService;

class AccountInfoReportFactory
{
    private const EURO_ISO_CODE = 'EUR';
    public const DEFAULT_CRON_EXECUTION_TIME = '1000-01-01';

    /**
     * @param array $accountInfoReport
     * @param bool $apiKeyEnabled
     * @return AccountInfoReportModel
     */
    public static function create(array $accountInfoReport, bool $apiKeyEnabled): AccountInfoReportModel
    {
        $systemService = new SystemService();
        $accountInfoReportModel = new AccountInfoReportModel();
        $accountInfoReportModel->Version = ConfigurationOptionManager::getPluginFilesVersion();
        $accountInfoReportModel->Installed = ConfigurationOptionManager::isPluginEnable();
        $accountInfoReportModel->MultiShop = $accountInfoReport['environment']['wp_multisite'];
        $accountInfoReportModel->PhpVersion = $accountInfoReport['environment']['php_version'];
        $accountInfoReportModel->MaxExecutionTime = $accountInfoReport['environment']['php_max_execution_time'];
        $accountInfoReportModel->DefaultCurrency = $accountInfoReport['settings']['currency'] === self::EURO_ISO_CODE;
        $accountInfoReportModel->Curl = $accountInfoReport['environment']['fsockopen_or_curl_enabled'];
        $accountInfoReportModel->FileFolderPermissions = $systemService->checkFileFolderPermissions();
        $accountInfoReportModel->ShopName = get_bloginfo('name');
        $accountInfoReportModel->RewritingSettingActive = ConfigurationOptionManager::existsPermalink();
        $accountInfoReportModel->DisabledRequiredFunctions = $systemService->getRequiredDisabledFunctions();

        $lastCronExecutionDate = $systemService->findCronExecutionLogFileDate();

        if ($lastCronExecutionDate) {
            $accountInfoReportModel->LastCronExecutionDate = $lastCronExecutionDate->format(DATE_W3C);
        }

        $accountInfoReportModel->WooCommerceVersion = $accountInfoReport['environment']['version'];
        $accountInfoReportModel->WordPressVersion = $accountInfoReport['environment']['wp_version'];
        $accountInfoReportModel->ApiKeyEnabled = $apiKeyEnabled;

        return $accountInfoReportModel;
    }

    /**
     * @param array $woocommerceReport
     * @return WoocommerceReportModel
     */
    public static function createWoocommerceReport(array $woocommerceReport): WoocommerceReportModel
    {
        $systemService = new SystemService();
        $lastCronExecutionDate = $systemService->findCronExecutionLogFileDate();
        $woocommerceReportModel = new WoocommerceReportModel();
        $woocommerceReportModel->LastCronExecutionDate = self::DEFAULT_CRON_EXECUTION_TIME;
        $woocommerceReportModel->MemoryLimit = $woocommerceReport['environment']['wp_memory_limit'];
        $woocommerceReportModel->MaxExecutionTime = $woocommerceReport['environment']['php_max_execution_time'];
        $woocommerceReportModel->DefaultCurrency = $woocommerceReport['settings']['currency'];

        if ($lastCronExecutionDate) {
            $woocommerceReportModel->LastCronExecutionDate = $lastCronExecutionDate->format('Y-m-d H:i:s');
        }

        return $woocommerceReportModel;
    }
}