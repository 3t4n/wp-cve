<?php

namespace WcMipConnector\Factory;

defined('ABSPATH') || exit;

use WcMipConnector\Model\CategoryReport;
use WcMipConnector\Model\FileReport;
use WcMipConnector\Model\ProductReport;
use WcMipConnector\Model\StatusReportModel;
use WcMipConnector\Model\VariationReport;

class StatusReportFactory
{
    /**
     * @param string $requestDate
     * @param array $accountInfoReport
     * @param array $languageReport
     * @param array $taxReport
     * @param FileReport $fileReport
     * @param ProductReport $productReport
     * @param CategoryReport $categoriesReport
     * @param VariationReport $variationsReport
     * @return StatusReportModel
     */
    public static function create(
        string $requestDate,
        array $accountInfoReport,
        array $languageReport,
        array $taxReport,
        FileReport $fileReport,
        ProductReport $productReport,
        CategoryReport $categoriesReport,
        VariationReport $variationsReport
    ): StatusReportModel {
        $statusReportModel = new StatusReportModel();

        $statusReportModel->RequestDate = $requestDate;
        $statusReportModel->AccountInfo = $accountInfoReport;
        $statusReportModel->Languages = $languageReport;
        $statusReportModel->Taxes = $taxReport;
        $statusReportModel->FilesReport = $fileReport;
        $statusReportModel->ProductsReport = $productReport;
        $statusReportModel->CategoriesReport = $categoriesReport;
        $statusReportModel->ProductVariationsReport = $variationsReport;

        return $statusReportModel;
    }
}