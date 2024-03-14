<?php

declare(strict_types=1);

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Factory\HealthReportFactory;
use WcMipConnector\Manager\ConfigurationOptionManager;
use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Manager\ImportProcessAttributeGroupManager;
use WcMipConnector\Manager\ImportProcessAttributeManager;
use WcMipConnector\Manager\ImportProcessBrandManager;
use WcMipConnector\Manager\ImportProcessCategoryManager;
use WcMipConnector\Manager\ImportProcessProductManager;
use WcMipConnector\Manager\ImportProcessTagManager;
use WcMipConnector\Manager\ImportProcessVariationManager;
use WcMipConnector\Manager\OrderLogManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Model\CatalogHealthReport;
use WcMipConnector\Model\OrderHealthReport;
use WcMipConnector\Model\SystemHealthReport;

class HealthReportService
{
    /** @var FileLogManager */
    protected $fileLogManager;

    /** @var SystemService */
    protected $systemService;

    /** @var ProductManager */
    protected $productManager;

    /** @var ImportProcessProductManager */
    protected $importProcessProductManager;

    /** @var ImportProcessVariationManager */
    protected $importProcessVariationManager;

    /** @var ImportProcessCategoryManager */
    protected $importProcessCategoryManager;

    /** @var ImportProcessBrandManager */
    protected $importProcessBrandManager;

    /** @var ImportProcessAttributeGroupManager */
    protected $importProcessAttributeGroupManager;

    /** @var ImportProcessAttributeManager */
    protected $importProcessAttributeManager;

    /** @var ImportProcessTagManager */
    protected $importProcessTagManager;

    /** @var OrderLogManager */
    protected $orderLogManager;

    /** @var ConfigurationOptionManager */
    protected $configurationOptionManager;

    /** @var LoggerService */
    protected $loggerService;

    public function __construct()
    {
        $this->fileLogManager = new FileLogManager();
        $this->systemService = new SystemService();
        $this->productManager = new ProductManager();
        $this->importProcessProductManager = new ImportProcessProductManager();
        $this->importProcessVariationManager = new ImportProcessVariationManager();
        $this->importProcessCategoryManager = new ImportProcessCategoryManager();
        $this->importProcessBrandManager = new ImportProcessBrandManager();
        $this->importProcessAttributeGroupManager = new ImportProcessAttributeGroupManager();
        $this->importProcessAttributeManager = new ImportProcessAttributeManager();
        $this->importProcessTagManager = new ImportProcessTagManager();
        $this->orderLogManager = new OrderLogManager();
        $this->configurationOptionManager = new ConfigurationOptionManager();
        $this->loggerService = new LoggerService();
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function get(): ?array
    {
        $fileHealthReport = $this->fileLogManager->getFileHealthReport();
        $systemHealthReport = $this->getSystemHealthReport();
        $catalogHealthReport = $this->getCatalogHealthReport();
        $orderHealthReport = $this->getOrderHealthReport();

        $accountInfoReportModel = HealthReportFactory::create(
            $fileHealthReport,
            $catalogHealthReport,
            $systemHealthReport,
            $orderHealthReport
        );

        return \json_decode(\json_encode($accountInfoReportModel), true);
    }

    private function getCatalogHealthReport(): CatalogHealthReport
    {
        $catalogHealthReport = new CatalogHealthReport();

        $catalogHealthReport->TotalMappedProductsCount = $this->productManager->countTotalMapped();
        $catalogHealthReport->TotalShopProductsCount = $this->productManager->countTotalProductShop();
        $catalogHealthReport->TotalMappedProductsActiveCount = $this->productManager->countTotalMappedAndActive();
        $catalogHealthReport->TotalMappedProductsDisabledCount = $this->productManager->countTotalMappedAndDisabled();
        $catalogHealthReport->TotalShopProductsDisabledCount = $this->productManager->countTotalProductShopDisabled();
        $catalogHealthReport->TotalShopProductsActiveCount = $this->productManager->countTotalProductShopActive();

        $catalogHealthReport->ProductErrorCount = $this->importProcessProductManager->countWithError();
        $catalogHealthReport->VariationErrorCount = $this->importProcessVariationManager->countWithError();
        $catalogHealthReport->CategoryErrorCount = $this->importProcessCategoryManager->countWithError();
        $catalogHealthReport->BrandErrorCount = $this->importProcessBrandManager->countWithError();
        $catalogHealthReport->TagErrorCount = $this->importProcessTagManager->countWithError();
        $catalogHealthReport->AttributeGroupErrorCount = $this->importProcessAttributeGroupManager->countWithError();
        $catalogHealthReport->AttributeErrorCount = $this->importProcessAttributeManager->countWithError();

        return $catalogHealthReport;
    }

    /**
     * @return OrderHealthReport
     */
    private function getOrderHealthReport(): OrderHealthReport
    {
        $orderHealthReport = new OrderHealthReport();

        try {
            $orderHealthReport->OrderNotMappedCount = $this->orderLogManager->countNotMapped();
        } catch (\Throwable $exception) {
            $this->loggerService->error('Exception found en OrderLogManager->countMapped(): '.$exception->getMessage());
        }

        try {
            $orderHealthReport->OrderMappedCount = $this->orderLogManager->countMapped();
        } catch (\Throwable $exception) {
            $this->loggerService->error('Exception found en OrderLogManager->countMapped(): '.$exception->getMessage());
        }

        return $orderHealthReport;
    }

    private function getSystemHealthReport(): SystemHealthReport
    {
        $systemHealthReport = new SystemHealthReport();

        $systemHealthReport->LastCronExecutionDate = $this->systemService->getCronExecutionDate();

        $systemStorageServerInfo = $this->systemService->getStorageServerInfo();
        $systemHealthReport->TotalHardDriveGb = $systemStorageServerInfo['Total'];
        $systemHealthReport->FreeHardDriveGb = $systemStorageServerInfo['Available'];

        $memoryInfo = $this->systemService->getMemoryInfo();
        $systemHealthReport->TotalMemoryMb = $memoryInfo['MemTotal'];
        $systemHealthReport->FreeMemoryMb = $memoryInfo['MemFree'];

        $systemHealthReport->DateLastModuleVersionCheck = $this->configurationOptionManager->getLastModuleUpdate() ? : null;
        $systemHealthReport->DateUpdateCarriers = $this->configurationOptionManager->getLastCarrierUpdate() ? : null;
        $systemHealthReport->DateUpdateStocks = $this->configurationOptionManager->getLastStockUpdate() ? : null;

        return $systemHealthReport;
    }
}