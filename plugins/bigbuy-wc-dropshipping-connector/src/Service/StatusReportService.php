<?php

namespace WcMipConnector\Service;

defined('ABSPATH') || exit;

use WcMipConnector\Factory\StatusReportFactory;
use WcMipConnector\Manager\CategoryManager;
use WcMipConnector\Manager\FileLogManager;
use WcMipConnector\Manager\ProductManager;
use WcMipConnector\Manager\VariationManager;

class StatusReportService
{
    /**@var AccountInfoReportService */
    protected $accountInfoReportService;
    /**@var LanguageReportService */
    protected $languageService;
    /**@var FileLogManager */
    protected $fileManager;
    /**@var ProductManager */
    protected $productManager;
    /**@var CategoryManager */
    protected $categoryManager;
    /**@var VariationManager */
    protected $variationManager;
    /** @var TaxesService */
    protected $taxService;

    public function __construct()
    {
        $this->accountInfoReportService = new AccountInfoReportService();
        $this->languageService = new LanguageReportService();
        $this->fileManager = new FileLogManager();
        $this->productManager = new ProductManager();
        $this->categoryManager = new CategoryManager();
        $this->variationManager = new VariationManager();
        $this->taxService = new TaxesService();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function get(): array
    {
        $timezone = new \DateTimeZone('UTC');
        $now = new \DateTime('now', $timezone);
        $requestDate = $now->format(DATE_W3C);

        $accountInfoReport = $this->accountInfoReportService->get();
        $languageReport = $this->languageService->getDefaultLanguageIsoCode();
        $productReport = $this->productManager->getProductReport();
        $categoriesReport = $this->categoryManager->getCategoryReport();
        $variationsReport = $this->variationManager->getVariationReport();
        $fileReport = $this->fileManager->getFileReport();
        $taxReport = $this->taxService->getTaxes();
        $statusReportFactory = StatusReportFactory::create(
            $requestDate,
            $accountInfoReport,
            $languageReport,
            $taxReport,
            $fileReport,
            $productReport,
            $categoriesReport,
            $variationsReport
        );

        return \json_decode(\json_encode($statusReportFactory), true);
    }
}